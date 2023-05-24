<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Illuminate\Support\Arr;
use Statik\Deploy\Dashboard\Page\DeploymentPage;
use Statik\Deploy\Dashboard\Table\PostsTable;
use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\DI;
use Statik\Deploy\Utils\Tooltip;

/**
 * @var DeploymentPage $this
 */
$deployment = new DeploymentManager();

$adminUrl = \add_query_arg(['page' => 'statik_deployment'], \admin_url('admin.php'));
$currentDeploy = $deployment->getLast(DeploymentInterface::IN_PROGRESS, false);
$frontendUrl = $deployment->getProvider()?->getFrontUrl();
$environmentsConfig = (array) DI::Config()->get('env', []);
$tab = \filter_input(\INPUT_GET, 'tab');

$environmentTitle = Arr::get($environmentsConfig[$deployment->getEnvironment()] ?? [], 'values.name.value')
    ?: \__('[Untitled environment]', 'statik-deployment');

\add_thickbox();

?>

<div id="statik" class="wrap">
    <h1><?= $GLOBALS['title']; ?></h1>

    <hr/>

    <?php if (\count($environmentsConfig)) { ?>
        <h2 class="nav-tab-wrapper">
            <?php foreach ($environmentsConfig as $name => $environment) { ?>
                <a href="<?= \add_query_arg('env', $name, $adminUrl); ?>"
                   class="nav-tab <?= $name === $deployment->getEnvironment() ? 'nav-tab-active' : null; ?>">
                    <?= Arr::get($environment, 'values.name.value')
                        ?: \__('[Untitled environment]', 'statik-deployment'); ?>
                </a>
            <?php } ?>
        </h2>

        <br>

        <div class="deployment js-confirm-wrapper">
            <div id="deployment_buttons">
                <div class="buttons">
                    <button class="button button-large button-primary js-deploy-trigger js-action-button"
                            onclick="return window.statikDeployment.instance.triggerDeployConfirm(event)">
                        <i class="dashicons-before dashicons-megaphone"> </i>
                        <?= \sprintf(\__('Deploy %s environment', 'statik-deployment'), $environmentTitle); ?>
                    </button>

                    <?php
                    /**
                     * Fire deployment page buttons action.
                     *
                     * @param string $environment environment name
                     */
                    \do_action('Statik\Deploy\deploymentPageButtons', $deployment->getEnvironment());
        ?>

                    <?php if ($frontendUrl) { ?>
                        <a class="button button-large button-second" href="<?= $frontendUrl; ?>" target="_blank">
                            <i class="dashicons-before dashicons-external"> </i>
                            <?= \sprintf(\__('Open %s frontend website', 'statik-deployment'), $environmentTitle); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if (empty($currentDeploy)) { ?>
            <div class="accordion post-changes">
                <?php $table = new PostsTable($deployment->getEnvironment()); ?>
                <?php $table->prepare_items(); ?>
                <?php $table->display(); ?>
            </div>
        <?php } ?>

        <div class="deployment-response">
            <?php foreach ($deployment->getStatusSteps() as $key => $step) { ?>
                <div class="single-step" data-type="<?= $step['type']; ?>" data-step-name="<?= $key; ?>">
                    <?= $step['spacer'] ?? false ? '<hr/>' : null; ?>
                    <div class="step-wrapper">
                        <div class="icon">
                            <div class="loader">Loading...</div>
                            <?php include __DIR__ . '/Icons/Error.php'; ?>
                            <?php include __DIR__ . '/Icons/Check.php'; ?>
                            <?php include __DIR__ . '/Icons/Skip.php'; ?>
                        </div>
                        <?php if ('error' === $step['type']) { ?>
                            <span class="label">
                                <strong><?= $step['label']; ?>: </strong>
                                <span class="error-message"> </span>
                            </span>
                        <?php } else { ?>
                            <span class="label">
                                <?= $step['label']; ?>
                                <?php if (isset($step['tooltip'])) { ?>
                                    <?= Tooltip::add($step['tooltip']); ?>
                                <?php } ?>
                            </span>
                            <span class="time"></span>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="summary">
                <div class="text">
                    <?= \__('Request finished successfully after ', 'statik-deployment'); ?>
                    <span class="summary-time"></span>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <?php $settingsUrl = \add_query_arg([
            'page' => 'statik_settings',
            'tab' => 'environments',
        ], \admin_url('admin.php')); ?>

        <div class="empty-page">
            <img src="<?= DI::url('assets/images/statik.svg'); ?>" alt="Statik">
            <h3><?= \__('There is nothing here yet', 'statik-deployment'); ?></h3>
            <p>
                <?= \sprintf(
            \__('Go to %s and add your first environment!', 'statik-deployment'),
            \sprintf('<a href="%s">%s</a>', $settingsUrl, \__('settings page', 'statik-deployment'))
        ); ?>
            </p>
        </div>
    <?php } ?>

    <div id="statik-deploy-log-modal" style="display:none;">
        <pre id="statik-deploy-log">
            <span class="loading"><?= \__('Log file content is loading...', 'statik-deployment'); ?></span>
            <span class="error r" style="display: none">
                <?= \__('Log file content cannot be loaded', 'statik-deployment'); ?>
            </span>
        </pre>
    </div>
</div>