<?php

declare(strict_types=1);

use Statik\Deploy\Dashboard\DeploymentLock;

\defined('WPINC') || exit;

/**
 * @var DeploymentLock $this
 */
$details = $this->showDeploymentDetails([]);
$deployInProgress = $details['statikDeploymentInProgress'] ?? null;

$hidden = $deployInProgress && ($_GET['page'] ?? '') !== 'statik_deployment' ? null : 'hidden';

?>
<div id="statik-deploy-lock-dialog" class="notification-dialog-wrap <?= $hidden; ?>" data-ajax="<?= (bool) $hidden; ?>">
    <div class="notification-dialog-background"></div>
    <div class="notification-dialog">
        <div class="locked-message">
            <div class="locked-header">
                <div class="locked-avatar"><?= $deployInProgress['labels']['avatar'] ?? ''; ?></div>
                <h3 class="title"><?= $deployInProgress['labels']['title'] ?? ''; ?></h3>
            </div>
            <div class="locked-content">
                <p>
                    <?= \__('During the deployment, you cannot perform any save and update actions in the Dashboard.'
                        . ' If you don\'t want to lose unsaved changes, just wait, this modal will disappear'
                        . ' after deployment is complete.', 'statik-deployment'); ?>
                </p>
            </div>
            <ol class="locked-steps"><?= $deployInProgress['labels']['steps'] ?? ''; ?></ol>
            <?php if (\current_user_can('manage_options')) { ?>
                <a class="button env-url" href="<?= $deployInProgress['env']['url'] ?? ''; ?>">
                    <?= \__('Show the deployment process', 'statik-deployment'); ?>
                </a>
            <?php } ?>
        </div>
    </div>
</div>

