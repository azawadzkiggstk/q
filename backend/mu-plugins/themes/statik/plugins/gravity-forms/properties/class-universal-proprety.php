<?php

declare(strict_types=1);

namespace Statik\Luna\GravityForms\Properties;

\defined('ABSPATH') || exit('Direct access is not permitted!');

use GF_Field;
use Statik\Luna\GravityForms\Fields\UniversalGraphQl;

/**
 * Class UniversalProperty.
 */
class UniversalProperty
{
    /**
     * UniversalProperty constructor.
     */
    public function __construct()
    {
        \add_filter('Statik\GraphQL\registerTypeConfig', [$this, 'addGraphQlProperty'], 10, 2);

        if ('gf_edit_forms' === ($_GET['page'] ?? false)) {
            \add_action('gform_field_standard_settings', [$this, 'createSettingField']);
            \add_filter('admin_footer', [$this, 'enqueueAssets']);
            \add_filter('gform_tooltips', [$this, 'addCustomTooltip']);
        }
    }

    /**
     * Get configuration for Universal field.
     */
    private function getConfig(): array
    {
        static $data;

        if (null !== $data) {
            return $data;
        }

        /**
         * Fire Gravity Forms universal field available options.
         *
         * @param array list of fields
         */
        $rawData = (array)\apply_filters(
            'Statik\Luna\gravityFormsUniversalField',
            [
                'Example field' => ['Example option' => 'text'],
                'Another field' => ['Additional option' => 'text', 'Another additional option' => 'number'],
            ]
        );

        foreach ($rawData as $optionKey => $extras) {
            if (\is_numeric($optionKey)) {
                continue;
            }

            $field = [
                'slug' => \lcfirst(\str_replace('-', '', \ucwords(\sanitize_title($optionKey), '-'))),
                'name' => $optionKey,
                'extra' => [],
            ];

            foreach ($extras as $extra => $type) {
                if (\is_numeric($extra)) {
                    continue;
                }

                $field['extra'][] = [
                    'slug' => \lcfirst(\str_replace('-', '', \ucwords(\sanitize_title($extra), '-'))),
                    'name' => $extra,
                    'type' => $type,
                ];
            }

            $data[] = $field;
        }

        return $data ?? [];
    }

    /**
     * Enqueue javascript that is required to make the field working correctly.
     */
    public function enqueueAssets(): void
    {
        $config = $this->getConfig();
        $extraFields = '';

        foreach ($config as $option) {
            foreach ($option['extra'] as $extra) {
                $id = "field_statik_universal_{$option['slug']}_{$extra['slug']}";
                $extraFields .= "document.getElementById(`{$id}`).value = field.{$extra['slug']} || '';";
                $extraFields .= \PHP_EOL;
            }
        } ?>
        <script>
          (() => {
            const universalField = document.getElementById('field_statik_universal');
            const universalExtraFields = document.getElementsByClassName(`statik_universal_extra_setting`);

            window.universalOnchange = () => {
              if (universalField.offsetParent !== null) {
                for (let el of universalExtraFields) {
                  el.style.display = el.classList.contains(`statik_universal_extra_${universalField.value}_setting`)
                      ? 'block'
                      : 'none';
                }
              }
            };

            gform.addAction(
                'gform_post_load_field_settings',
                ([field]) => {
                  universalField.value = field.universal || '<?= \reset($config)['slug']; ?>';
                    <?= $extraFields; ?>;
                  window.universalOnchange();
                },
            );
          })();
        </script>
        <?php
    }

    /**
     * Add settings field in the Gravity Forms form editor screen.
     */
    public function createSettingField(int $position): void
    {
        if (400 === $position) { ?>
            <li class="statik_universal_setting field_setting">
                <label for="field_statik_universal" class="section_label">
                    <?= \__('Universal field', 'statik-luna'); ?>
                    <?= \gform_tooltip('form_field_statik_universal', true); ?>
                </label>
                <select id="field_statik_universal"
                        onchange="SetFieldProperty('universal', this.value); window.universalOnchange()">
                    <?php foreach ($this->getConfig() as $option) { ?>
                        <option value="<?= $option['slug']; ?>"><?= $option['name']; ?></option>
                    <?php } ?>
                </select>
            </li>

            <?php foreach ($this->getConfig() as $option) { ?>
                <?php foreach ($option['extra'] as $extra) { ?>
                    <li class="statik_universal_extra_<?= $option['slug']; ?>_setting statik_universal_extra_setting field_setting"
                        style="display: none;">
                        <label for="field_statik_universal_<?= $option['slug']; ?>_<?= $extra['slug']; ?>"
                               class="section_label">
                            <?= $extra['name']; ?>
                        </label>
                        <input type="<?= $extra['type']; ?>"
                               id="field_statik_universal_<?= $option['slug']; ?>_<?= $extra['slug']; ?>"
                               style="max-height:2.25rem;min-height:2.25rem;width:100%;padding:0 .75rem;"
                               onchange="SetFieldProperty('<?= $extra['slug']; ?>', this.value);"
                    </li>
                <?php } ?>
            <?php } ?>
        <?php }
    }

    /**
     * Register custom tooltips that are required for the field.
     */
    public function addCustomTooltip(array $tooltips): array
    {
        $tooltips['form_field_statik_universal'] = \sprintf(
            '<strong>%s</strong>%s',
            \__('Chose Universal field', 'statik-luna'),
            \__('Make the field disabled and disallow modify the value.', 'statik-luna')
        );

        return $tooltips;
    }

    /**
     * Register new field in Statik GraphQL plugin.
     */
    public function addGraphQlProperty(mixed $config, string $typeName): mixed
    {
        if ($typeName !== 'UniversalField' || false === \is_array($config)) {
            return $config;
        }

        \register_graphql_object_type(
            'UniversalExtras',
            [
                'description' => \__('Universal field extra attributes.', 'statik-luna'),
                'fields' => [
                    'key' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('Universal field extra attribute key.', 'statik-luna'),
                    ],
                    'value' => [
                        'type' => 'String',
                        'description' => \__('Universal field extra attribute value.', 'statik-luna'),
                    ],
                ],
            ]
        );

        $config['fields']['universal'] = [
            'type' => 'String',
            'description' => \__('Determines which universal field type is used.', 'statik-luna'),
            'resolve' => static fn (GF_Field $field): ?string => $field['universal'] ?? null,
        ];

        $config['fields']['universalExtra'] = [
            'type' => ['list_of' => 'UniversalExtras'],
            'resolve' => function (GF_Field $field): ?array {
                foreach ($this->getConfig() as $option) {
                    if ($option['slug'] !== $field['universal']) {
                        continue;
                    }

                    foreach ($option['extra'] as $extra) {
                        $values[] = [
                            'key' => (string)$extra['slug'],
                            'value' => (string)$field[$extra['slug']] ?? null,
                        ];
                    }
                }

                return $values ?? null;
            },
        ];

        return $config;
    }
}
