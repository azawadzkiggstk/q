<?php

declare(strict_types=1);

namespace Statik\Deploy\Common\Settings\Field;

use Statik\Deploy\Common\Settings\Generator;
use Statik\Deploy\Common\Settings\GeneratorInterface;

/**
 * Class Repeater.
 */
class Repeater extends AbstractField
{
    private array $templateFields;

    /**
     * Repeater constructor.
     */
    public function __construct(string $name, array $structure, GeneratorInterface $generator)
    {
        parent::__construct($name, $structure, $generator);

        foreach ($structure['fields'] ?? [] as $key => $field) {
            $structure['fields'][$key]["{$name}.value.template-key.{$key}"] = $field;
        }

        $this->templateFields = $structure['fields'] ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function generateFieldHtml(): string
    {
        $generator = new Generator($this->config, $this->namespace, $this->apiNamespace);

        if (\is_array($this->value) && false === empty($this->value)) {
            foreach (\array_keys((array) $this->value) as $valueKey) {
                $fields = [];

                foreach ($this->templateFields as $fieldKey => $field) {
                    $fields["{$this->name}.value.{$valueKey}.{$fieldKey}"] = $field;
                }

                $generator->registerFields("repeater_fields_{$valueKey}", $fields);
            }
        }

        foreach ($this->templateFields as $key => $templateField) {
            $templateFields["{$this->name}.value.template-key.{$key}"] = $templateField;
        }

        $generator->registerFields('repeater_template_fields', $templateFields ?? []);
        $generator->initializeFields(false);

        return $this->getFieldHtml($generator);
    }

    /**
     * Generate field HTML.
     */
    private function getFieldHtml(Generator $generator): string
    {
        $templateStructure = \str_replace(
            ['name="', 'required="'],
            ['data-template-name="', 'data-template-required="'],
            $generator->generateStructure('repeater_template_fields')
        );

        \ob_start(); ?>

        <input type="hidden" name="<?= "{$this->namespace}[{$this->name}.value]"; ?>" value="">
        <div class="js-repeater-wrapper">

            <div class="js-fields-wrapper">
                <?php if (\is_array($this->value) && false === empty($this->value)) { ?>
                    <?php foreach (\array_keys((array) $this->value) as $valueKey) { ?>
                        <div class="repeater-row js-repeater-row" data-key="<?= $valueKey; ?>">
                            <?= $generator->generateStructure("repeater_fields_{$valueKey}"); ?>
                            <div class="float-right">
                                <button class="js-remove-row button button-small button-delete" type="button">
                                    <?= \__('Remove', 'statik-commons'); ?>
                                </button>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <div class="js-template">
                <div class="repeater-row js-repeater-row" data-key="template-key">
                    <?= $templateStructure; ?>
                    <div class="float-right">
                        <button class="js-remove-row button button-small button-delete" type="button">
                            <?= \__('Remove', 'statik-commons'); ?>
                        </button>
                    </div>
                </div>
            </div>

            <button class="js-add-row button button-small button-primary"><?= \__('Add new', 'statik-commons'); ?></button>
        </div>

        <?php return \ob_get_clean();
    }
}
