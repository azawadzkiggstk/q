<?php

declare(strict_types=1);

namespace Statik\Deploy\Dashboard\Table;

use Statik\Deploy\Deployment\Logger\PostLogger;
use Statik\Deploy\DI;

/**
 * Class PostTable.
 */
class PostsTable extends \WP_List_Table
{
    /** @var int */
    public const ITEMS_PER_PAGE = 15;

    private string $environment;

    /**
     * HistoryTable constructor.
     */
    public function __construct(string $environment)
    {
        parent::__construct([
            'singular' => \__('Changes since the last deployment', 'statik-deployment'),
            'plural' => \__('Changes since the last deployment', 'statik-deployment'),
        ]);

        $this->environment = $environment;
    }

    /**
     * Handle default column.
     *
     * @param array  $item        Table single item
     * @param string $column_name Column name
     */
    public function column_default($item, $column_name): string
    {
        return match ($column_name) {
            'id', 'name', 'type', 'status', 'last_update' => $item[$column_name] ?? '',
            default => \print_r($item, true),
        };
    }

    /**
     * Prepare name column.
     */
    public function column_name(array $item): string
    {
        if ('Deleted' === $item['status']) {
            return '';
        }

        $post = \get_post($item['id']);

        if ('trash' === $post->post_status) {
            $actions = [
                'untrash' => \sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    \wp_nonce_url(
                        \add_query_arg(['action' => 'untrash', 'post' => $post->ID], 'post.php'),
                        "untrash-post_{$post->ID}"
                    ),
                    \__('Restore', 'statik-deployment')
                ),
                'delete' => \sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    \get_delete_post_link($post->ID, null, true),
                    \__('Delete Permanently', 'statik-deployment')
                ),
            ];
        } else {
            $actions = [
                'edit' => \sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    \get_edit_post_link($post->ID),
                    \__('Edit', 'statik-deployment')
                ),
                'view' => \sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    \get_permalink($post->ID),
                    \__('View', 'statik-deployment')
                ),
                'delete' => \sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    \get_delete_post_link($post->ID),
                    \__('Delete', 'statik-deployment')
                ),
            ];
        }

        if ($item['revision_url']) {
            $actions['revision'] = \sprintf(
                '<a href="%s" target="_blank">%s</a>',
                $item['revision_url'],
                \__('View revisions', 'statik-deployment')
            );
        }

        /**
         * Fire when column actions are added.
         *
         * @param array    $actions column actions
         * @param \WP_Post $post    WP Post instance
         *
         * @return array
         */
        $actions = (array) \apply_filters('Statik\Deploy\postsColumnActions', $actions, $post);

        return \sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions));
    }

    /**
     * {@inheritdoc}
     */
    public function prepare_items(): void
    {
        $this->_column_headers = [$this->get_columns(), [], $this->get_sortable_columns()];

        $posts = PostLogger::getPostsDetails($this->environment);
        \usort($posts, [&$this, 'sort_data']);

        $offset = static::ITEMS_PER_PAGE * $this->get_pagenum() - static::ITEMS_PER_PAGE;
        $this->items = \array_slice($posts, $offset, static::ITEMS_PER_PAGE);

        $this->set_pagination_args(['total_items' => \count($posts), 'per_page' => static::ITEMS_PER_PAGE]);
    }

    /**
     * {@inheritDoc}
     */
    public function no_items(): void
    {
        include_once DI::dir('src/Partials/Tables/PostsNoResults.php');
    }

    /**
     * {@inheritdoc}
     */
    public function get_columns(): array
    {
        return [
            'name' => \__('Post name', 'statik-deployment'),
            'type' => \__('Post type', 'statik-deployment'),
            'status' => \__('Status', 'statik-deployment'),
            'last_update' => \__('Last update', 'statik-deployment'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function get_sortable_columns(): array
    {
        return [
            'name' => ['name', false],
            'type' => ['type', false],
            'status' => ['status', false],
            'last_update' => ['last_update', 'desc'],
        ];
    }

    /**
     * Sort elements.
     */
    private function sort_data(array $a, array $b)
    {
        $type = ($_GET['order'] ?? '') === 'asc';

        switch ($_GET['orderby'] ?? '') {
            case 'name':
                return $type
                    ? $a['name'] <=> $b['name']
                    : $b['name'] <=> $a['name'];
            case 'type':
                return $type
                    ? $a['type'] <=> $b['type']
                    : $b['type'] <=> $a['type'];
            case 'status':
                return $type
                    ? $a['status'] <=> $b['status']
                    : $b['status'] <=> $a['status'];
            case 'last_update':
                return $type
                    ? $a['time'] <=> $b['time']
                    : $b['time'] <=> $a['time'];
        }
    }
}
