<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Illuminate\Support\Arr;
use Statik\Deploy\Dashboard\Page\SettingsPage;
use Statik\Deploy\DI;
use Statik\Deploy\Utils\Environment;

/**
 * @var SettingsPage $this
 */
$adminUrl = \add_query_arg(['page' => 'statik_settings', 'tab' => 'environments'], \admin_url('admin.php'));
$environmentsConfig = (array) DI::Config()->get('env', []);
$environmentName = Environment::getEnvironmentName();
$environmentsCount = \count($environmentsConfig);

?>

<div class="nav-tab-wrapper smaller">
    <?php foreach ($environmentsConfig as $name => $environment) { ?>
        <a href="<?= \add_query_arg('env', $name, $adminUrl); ?>"
           class="nav-tab <?= $name === $environmentName ? 'nav-tab-active' : null; ?>">
            <?= Arr::get($environment, 'values.name.value') ?: \__('[Untitled environment]', 'statik-deployment'); ?>
        </a>
    <?php } ?>
    <div class="nav-tab create-new js-create-new-environment"
         onclick="window.statikDeployment.instance.createEnvironmentButton(this)">
        <span style="display: <?= $environmentsCount ? 'block' : 'none'; ?>">+</span>
        <form method="POST" style="display: <?= $environmentsCount ? 'none' : 'block'; ?>">
            <?php \wp_nonce_field('statik_deployment_env_nonce', '_deploy_env_nonce'); ?>
            <input type="hidden" name="statik_deployment_env[action]" value="add">
            <input type="text" name="statik_deployment_env[env]" aria-label="Environment name"
                   placeholder="Environment name" required="required" pattern="[a-zA-Z0-9\s]+"
                   autofocus>
            <input type="submit" value="<?= \__('Add', 'statik-deployment'); ?>" class="button">
        </form>
        <?php if (0 === $environmentsCount) { ?>
            <div class="env-tooltip"><?= \__('Let\'s add your first environment!', 'statik-deployment'); ?></div>
        <?php } ?>
    </div>
</div>

<?php if ($environmentsCount) { ?>
    <form method="POST">
        <?php \wp_nonce_field('statik_deployment_settings_nonce', '_deploy_nonce'); ?>
        <?= DI::Generator()->generateStructure('environments'); ?>
        <?= \get_submit_button('', 'primary', 'submit', false); ?>
    </form>

    <form method="POST" class="delete-environment js-confirm-wrapper"
          onsubmit="window.statikDeployment.instance.deleteEnvironmentConfirm(event)">
        <?php \wp_nonce_field('statik_deployment_env_nonce', '_deploy_env_nonce'); ?>
        <input type="hidden" name="statik_deployment_env[action]" value="remove">
        <input type="hidden" name="statik_deployment_env[env]" value="<?= $environmentName; ?>">
        <input type="submit" value="<?= \__('Delete environment', 'statik-deployment'); ?>"
               class="button button-remove top-20">
    </form>
<?php } else { ?>
    <div class="empty-page">
        <img src="<?= DI::url('assets/images/statik.svg'); ?>" alt="Statik">
        <h3><?= \__('There is nothing here yet', 'statik-deployment'); ?></h3>
    </div>
<?php } ?>
