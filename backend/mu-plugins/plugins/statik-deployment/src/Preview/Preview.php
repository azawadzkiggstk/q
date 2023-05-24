<?php

declare(strict_types=1);

namespace Statik\Deploy\Preview;

use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\Deployment\Error\DeploymentException;
use Statik\Deploy\DI;

/**
 * Class Preview.
 */
class Preview
{
    /**
     * Preview constructor.
     */
    public function __construct()
    {
        if (false === DI::isPreview()) {
            return;
        }

        \add_action('template_redirect', [$this, 'handlePreviewPage'], 50);
    }

    /**
     * Handle preview page.
     */
    public function handlePreviewPage(): void
    {
        if (false === \is_preview()) {
            return;
        }

        \show_admin_bar(false);

        global $post;

        $deployId = \filter_input(\INPUT_GET, 'deploy_id', \FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $environment = DI::Config()->getValue('settings.preview');

        try {
            $deploymentManager = new DeploymentManager($environment);
            $deployment = $deployId
                ? $deploymentManager->get($deployId)
                : $deploymentManager->create("Preview #{$post->ID}", false, false, $post->ID, true);

            if (false === $deployment->isPreview()) {
                return;
            }

            if ($deployment->getMeta('posts') !== [$post->ID]) {
                return;
            }

            if (null === $deployId) {
                \wp_redirect(\add_query_arg('deploy_id', $deployment->getId()));
                exit;
            }
        } catch (DeploymentException $exception) {
        }

        include_once DI::dir('src/Partials/Preview/PreviewTrigger.php');

        exit;
    }
}
