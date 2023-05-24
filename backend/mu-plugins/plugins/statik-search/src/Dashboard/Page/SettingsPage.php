<?php

declare(strict_types=1);

namespace Statik\Search\Dashboard\Page;

use Statik\Search\Common\Dashboard\DashboardInterface;
use Statik\Search\Common\Dashboard\Page\AbstractPage;
use Statik\Search\Common\Dashboard\Page\SettingsPageInterface;
use Statik\Search\DI;
use Statik\Search\Search\SearchManager;

/**
 * Class NetworkSettingsPage.
 */
class SettingsPage extends AbstractPage implements SettingsPageInterface
{
    /**
     * NetworkSettingsPage constructor.
     */
    public function __construct(DashboardInterface $dashboard)
    {
        parent::__construct($dashboard);

        \add_filter('Statik\Common\settingsTabs', [$this, 'addSettingsTab'], 15);
        \add_action('Statik\Common\settingsPageTabs', [$this, 'getSettingsPage'], 15, 2);
    }

    /**
     * Add new tab to the plugins Settings page.
     */
    public function addSettingsTab(array $tabs): array
    {
        return \array_merge($tabs, ['search' => 'Search settings']);
    }

    /**
     * Get settings page and set required variables.
     */
    public function getSettingsPage(string $currentTab): void
    {
        'search' === $currentTab && include_once DI::dir('src/Partials/SettingsPage.php');
    }

    /**
     * Get custom settings for environment.
     */
    public function getSettingsFields(): void
    {
        DI::Generator()->registerFields(
            'search_settings',
            [
                'api.header' => [
                    'type' => 'heading',
                    'label' => \__('Search API settings', 'statik-search'),
                ],
                'search.endpoint_url' => [
                    'type' => 'input',
                    'label' => \__('API hook', 'statik-search'),
                    'description' => \__(
                        'The URL of the API endpoint that can be used to manage search engine.',
                        'statik-search'
                    ),
                    'attrs' => ['class' => 'regular-text'],
                ],
                'search.client_key' => [
                    'type' => 'input',
                    'label' => \__('API token', 'statik-search'),
                    'description' => \__('Token used to authorize API calls.', 'statik-search'),
                    'attrs' => ['class' => 'regular-text', 'type' => 'password'],
                ],
                'search.path' => [
                    'type' => 'input',
                    'label' => \__('Site path prefix', 'statik-search'),
                    'description' => \__(
                        'This prefix is used during the build in case of using a multisite instance. Mostly,'
                        . ' the value is equal to WP multisite blog path.',
                        'statik-search'
                    ),
                    'value' => [SearchManager::class, 'getSitePrefix'],
                    'attrs' => ['class' => 'regular-text', 'disabled' => true, 'required' => true],
                ],
                'search.cpt' => [
                    'type' => 'input:checkbox',
                    'label' => \__('Supported custom post types', 'statik-search'),
                    'description' => \__(
                        'Posts from selected CPTs will be displayed in search results.',
                        'statik-search'
                    ),
                    'values' => static function (): array {
                        $allPostTypes = \get_post_types(['public' => true, 'exclude_from_search' => false], 'objects');

                        foreach ($allPostTypes as $postType) {
                            $postTypes[$postType->name] = $postType->label;
                        }

                        return $postTypes ?? [];
                    },
                    'default' => ['post' => 1, 'page' => 1],
                    'attrs' => ['class' => 'regular-text'],
                ],
                'engine.break' => [
                    'type' => 'break',
                ],
                'engine.header' => [
                    'type' => 'heading',
                    'label' => \__('Search engine settings', 'statik-search'),
                    'description' => \__(
                        'These settings will be saved during the next search engine update!',
                        'statik-search'
                    ),
                ],
                'engine.attributes_to_retrieve' => [
                    'type' => 'textarea',
                    'label' => \__('Attributes to retrieve', 'statik-search'),
                    'description' => \__(
                        'This parameter controls which attributes to retrieve and which not to retrieve.'
                        . ' This setting helps to improve performance by reducing the size of records in the'
                        . ' search response. Reducing the size of records leads to faster network transfers.',
                        'statik-search'
                    ),
                    'default' => "title\nurl\nexcerpt\ntaxonomies",
                    'attrs' => [
                        'class' => 'regular-text',
                        'rows' => 5,
                        'placeholder' => \__(
                            'Put each attribute priority in the following line, e.g.:&#10;attribute1&#10;attribute2',
                            'statik-search'
                        ),
                    ],
                ],
                'engine.searchable_attributes' => [
                    'type' => 'textarea',
                    'label' => \__('Searchable attributes', 'statik-search'),
                    'description' => \__(
                        'The complete list of attributes used for searching. This setting is critical to establishing'
                        . ' excellent relevance for two main reasons: it limits the scope of a search and it creates a'
                        . ' priority order.',
                        'statik-search'
                    ),
                    'default' => "title\nexcerpt\ncontent\ntaxonomies",
                    'attrs' => [
                        'class' => 'regular-text',
                        'rows' => 5,
                        'placeholder' => \__(
                            'Put each comma-separated attribute priority in the following line,'
                            . ' e.g.:&#10;attribute1, attribute2&#10;attribute3',
                            'statik-search'
                        ),
                    ],
                ],
                'engine.attributes_for_faceting' => [
                    'type' => 'textarea',
                    'label' => \__('Filterable attributes', 'statik-search'),
                    'description' => \__(
                        'The complete list of attributes that will be used for filtering. Use this setting to make any'
                        . ' string attribute filterable.',
                        'statik-search'
                    ),
                    'attrs' => [
                        'class' => 'regular-text',
                        'rows' => 5,
                        'placeholder' => \__(
                            'Put each attribute priority in the following line, e.g.:&#10;attribute1&#10;attribute2',
                            'statik-search'
                        ),
                    ],
                ],
                'debug.break' => [
                    'type' => 'break',
                ],
                'debug.header' => [
                    'type' => 'heading',
                    'label' => \__('Debug settings', 'statik-search'),
                ],
                'settings.debug' => [
                    'type' => 'input:checkbox',
                    'label' => \__('Debug mode', 'statik-search'),
                    'description' => \__('Whether plugin requests should execute in "debug" mode.', 'statik-search'),
                    'values' => ['debug' => ''],
                    'attrs' => ['class' => 'regular-text', 'ui' => true],
                ],
            ]
        );
    }
}
