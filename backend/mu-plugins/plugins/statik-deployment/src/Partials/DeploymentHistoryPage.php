<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Illuminate\Support\Arr;
use Statik\Deploy\Dashboard\Page\DeploymentHistoryPage;
use Statik\Deploy\Dashboard\Table\HistoryTable;
use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\DI;

/**
 * @var DeploymentHistoryPage $this
 */
$deployment = new DeploymentManager();

$adminUrl = \add_query_arg(['page' => 'statik_deployment_history'], \admin_url('admin.php'));
$frontendUrl = $deployment->getProvider()?->getFrontUrl();
$environmentsConfig = (array) DI::Config()->get('env', []);
$tab = \filter_input(\INPUT_GET, 'tab');

$envPageUrl = \get_admin_url(
    null,
    \add_query_arg(
        ['page' => 'statik_deployment', 'env' => $deployment->getEnvironment(), 'tab' => 'changes'],
        'admin.php'
    )
);

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

        <div class="deployment">
            <div id="deployment_buttons">
                <div class="buttons">
                    <a class="button button-large button-second" href="<?= $envPageUrl; ?>">
                        <i class="dashicons-before dashicons-megaphone"> </i>
                        <?= \sprintf(
                            \__('Go to deploy %s environment page', 'statik-deployment'),
                            $environmentTitle
                        ); ?>
                    </a>

                    <?php if ($frontendUrl) { ?>
                        <a class="button button-large button-second" href="<?= $frontendUrl; ?>" target="_blank">
                            <i class="dashicons-before dashicons-external"> </i>
                            <?= \sprintf(\__('Open %s frontend website', 'statik-deployment'), $environmentTitle); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="deployment">
            <div class="last-deploy js-confirm-wrapper">
                <?php $table = new HistoryTable($deployment->getEnvironment()); ?>
                <?php $table->prepare_items(); ?>
                <?php $table->display(); ?>
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