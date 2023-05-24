<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Logger;

use Statik\Deploy\DI;
use Statik\Deploy\Utils\Tooltip;

/**
 * Class PostLogger.
 */
class PostLogger
{
    /**
     * PostLogger constructor.
     */
    public function __construct()
    {
        \add_filter('wp_insert_post_parent', [$this, 'onPostInsert'], 10, 2);
        \add_action('delete_post', [$this, 'onPostDelete']);
        \add_action('Statik\Deploy\afterDeploymentSuccess', [$this, 'onDeployFinished']);
    }

    /**
     * Hook fired when post is created or updated.
     */
    public function onPostInsert(int $postParent, int $postId): int
    {
        if (0 === $postId) {
            return $postParent;
        }

        $supportedCpt = (array) DI::Config()->getValue('logger.cpt', []);

        if (false === \in_array(\get_post_type($postId), $supportedCpt)) {
            return $postParent;
        }

        foreach ((array) DI::Config()->getKeys('env') as $environment) {
            $this->updateEnvLogger($environment, $postId);
        }

        DI::Config()->save();

        return $postParent;
    }

    /**
     * Hook fired when post is deleted.
     */
    public function onPostDelete(int $postId): void
    {
        $supportedCpt = (array) DI::Config()->getValue('logger.cpt', []);

        if (false === \in_array(\get_post_type($postId), $supportedCpt)) {
            return;
        }

        foreach ((array) DI::Config()->getKeys('env') as $environment) {
            $this->updateEnvLogger($environment, $postId);
        }

        DI::Config()->save();
    }

    /**
     * Update environment logger.
     */
    private function updateEnvLogger(string $environment, int $postId): void
    {
        $logger = DI::Config()->get("env.{$environment}.logger", []);
        $logger[$postId] = [\get_current_user_id(), \time()];
        DI::Config()->set("env.{$environment}.logger", $logger);
    }

    /**
     * Flush logger when deploy finishes.
     */
    public function onDeployFinished(array $data): void
    {
        DI::Config()->delete("env.{$data['environment']}.logger");
        DI::Config()->save();
    }

    /**
     * Prepare posts for display in table.
     */
    public static function getPostsDetails(string $environment, bool $raw = false): array
    {
        $supportedCpt = (array) DI::Config()->getValue('logger.cpt', []);
        $postsIds = (array) DI::Config()->get("env.{$environment}.logger", []);
        $posts = [];

        $undefinedAuthorNote = Tooltip::add(\__('The author of the change cannot be recognized.', 'statik-deployment'));

        $now = \current_time('timestamp');
        $timeFormat = \get_option('date_format') . ' ' . \get_option('time_format');

        foreach ($postsIds as $id => [$author, $time]) {
            if (\is_array($author)) {
                continue;
            }

            if ($raw) {
                $posts[] = $id;

                continue;
            }

            $post = \get_post($id);

            if (null === $post) {
                continue;
            }

            if (false === \in_array($post->post_type, $supportedCpt)) {
                continue;
            }

            $lastRevision = \wp_revisions_enabled($post)
                ? \array_values(\wp_get_post_revisions($post->ID))[0] ?? null
                : null;

            $lastChangeAuthor = $author ? \get_user_by('ID', $author)->display_name : '[Undefined]';

            $postModified = \wp_date($timeFormat, \strtotime($post->post_modified));

            $posts[] = [
                'id' => $id,
                'name' => \sprintf('%s (ID: %s)', $post->post_title ?: '[Untitled post]', $id),
                'type' => \ucfirst($post->post_type),
                'status' => \ucfirst($post->post_status),
                'last_update' => \sprintf(
                    '~%s ago by <strong>%s</strong><div class="row-actions">%s</div>',
                    \human_time_diff(\strtotime($post->post_modified), $now),
                    'Undefined' === $lastChangeAuthor ? $undefinedAuthorNote : $lastChangeAuthor,
                    $postModified,
                ),
                'time' => \strtotime($post->post_modified),
                'revision_url' => $lastRevision ? \get_edit_post_link($lastRevision->ID) : '',
            ];
        }

        $raw || \usort($posts, static fn (array $a, array $b): int => -($a['time'] <=> $b['time']));

        return $posts;
    }
}
