<?php

declare(strict_types=1);

namespace Statik\Luna\GravityForms\Properties;

\defined('ABSPATH') || exit('Direct access is not permitted!');

use GF_Field;
use RGCurrency;

/**
 *  Class NumberCurrencyProperty.
 */
class NumberCurrencyProperty
{
    /**
     * NumberCurrencyProperty constructor.
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
     * Get crypto currencies list.
     */
    public static function cryptocurrencies(): array
    {
        return [
            'BTC' => [
                'name' => \__('Bitcoin', 'statik-luna'),
                'symbol_left' => '₿',
                'symbol_right' => '',
                'symbol_padding' => '',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 10,
            ],
            'ETH' => [
                'name' => \__('Ethereum', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'ETH',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 8,
            ],
            'XRP' => [
                'name' => \__('XRP', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'XRP',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 4,
            ],
            'LTC' => [
                'name' => \__('LTC', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'LTC',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 4,
            ],
            'XLM' => [
                'name' => \__('XLM', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'XLM',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 4,
            ],
            'DASH' => [
                'name' => \__('DASH', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'DASH',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 4,
            ],
            'TRX' => [
                'name' => \__('TRX', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'TRX',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 4,
            ],
            'DOGE' => [
                'name' => \__('DOGE', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'DOGE',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 4,
            ],
            'VEN' => [
                'name' => \__('VEN', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'VEN',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 4,
            ],
            'USDT' => [
                'name' => \__('USDT', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'USDT',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 2,
            ],
            'BNB' => [
                'name' => \__('BNB', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'BNB',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 4,
            ],
            'ADA' => [
                'name' => \__('ADA', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'ADA',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_sepaator' => '.',
                'decimals' => 4,
            ],
            'USDC' => [
                'name' => \__('USDC', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'USDC',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => ' . ',
                'decimals' => 2,
            ],
            'BCH' => [
                'name' => \__('BCH', 'statik-luna'),
                'symbol_left' => '',
                'symbol_right' => 'BCH',
                'symbol_padding' => ' ',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 4,
            ],
        ];
    }

    /**
     * Enqueue javascript that is required to make the field working correctly.
     */
    public function enqueueAssets(): void
    {
        ?>
        <script>
          (() => gform.addAction(
              'gform_post_load_field_settings',
              ([field]) => document.getElementById('field_statik_currency')
                  .value = field.currency || '<?= \get_option('rg_gforms_currency', 'USD'); ?>',
          ))();
        </script>
        <?php
    }

    /**
     * Add settings field in the Gravity Forms form editor screen.
     */
    public function createSettingField(int $position): void
    {
        if (1500 === $position) { ?>
            <li class="statik_currency_setting field_setting">
                <label for="field_statik_currency" class="section_label">
                    <?= \__('Currency', 'statik-luna'); ?>
                    <?= \gform_tooltip('form_field_statik_currency', true); ?>
                </label>
                <select id="field_statik_currency" onchange="SetFieldProperty('currency', this.value);">
                    <optgroup label="Currencies">
                        <?php foreach (RGCurrency::get_currencies() as $currency => $currencyConfig) { ?>
                            <option value="<?= $currency; ?>"><?= $currencyConfig['name']; ?></option>
                        <?php } ?>
                    </optgroup>
                    <optgroup label="Cryptocurrencies">
                        <?php foreach (self::cryptocurrencies() as $currency => $currencyConfig) { ?>
                            <option value="<?= $currency; ?>"><?= $currencyConfig['name']; ?></option>
                        <?php } ?>
                    </optgroup>
                </select>
            </li>
        <?php }
    }

    /**
     * Register custom tooltips that are required for the field.
     */
    public function addCustomTooltip(array $tooltips): array
    {
        $tooltips['form_field_statik_currency'] = \sprintf(
            '<strong>%s</strong>%s',
            \__('Currency', 'statik-luna'),
            \__('Define the currency that should be enabled in the field.', 'statik-luna')
        );

        return $tooltips;
    }

    /**
     * Register new field in Statik GraphQL plugin.
     */
    public function addGraphQlProperty(mixed $config, string $typeName): mixed
    {
        if ($typeName !== 'NumberFinancialField' || false === \is_array($config)) {
            return $config;
        }

        \register_graphql_object_type(
            'NumberCurrency',
            [
                'description' => \__('Specifies the currency supported in a financial number field.', 'statik-luna'),
                'fields' => [
                    'currency' => [
                        'type' => 'string',
                        'description' => \__('The currency name.', 'statik-luna'),
                        'resolve' => static fn (?array $currency): ?string => $currency ? $currency['name'] : null,
                    ],
                    'symbolLeft' => [
                        'type' => 'string',
                        'description' => \__('The currency left symbol.', 'statik-luna'),
                        'resolve' =>
                            static fn (?array $currency): ?string => $currency ? $currency['symbol_left'] : null,
                    ],
                    'symbolRight' => [
                        'type' => 'string',
                        'description' => \__('The currency right symbol.', 'statik-luna'),
                        'resolve' =>
                            static fn (?array $currency): ?string => $currency ? $currency['symbol_right'] : null,
                    ],
                    'symbolPadding' => [
                        'type' => 'string',
                        'description' => \__('The currency symbol padding.', 'statik-luna'),
                        'resolve' =>
                            static fn (?array $currency): ?string => $currency ? $currency['symbol_padding'] : null,
                    ],
                    'decimals' => [
                        'type' => 'number',
                        'description' => \__('The currency decimals.', 'statik-luna'),
                        'resolve' =>
                            static fn (?array $currency): ?int => $currency ? (int)$currency['decimals'] : null,
                    ],
                ]
            ]
        );

        $config['fields']['currency'] = [
            'type' => 'NumberCurrency',
            'description' => \__('Specifies the currency supported in a financial number field.', 'statik-luna'),
            'resolve' => static fn (GF_Field $field): ?array => RGCurrency::get_currencies()[$field['currency']]
                ?? self::cryptocurrencies()[$field['currency']]
                ?? RGCurrency::get_currencies()[\get_option('rg_gforms_currency', 'USD')],
        ];

        return $config;
    }
}
