<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\Deploy\Dashboard\AdminBar;

/**
 * @var AdminBar $this
 * @var string   $envPageUrl
 * @var string   $envName
 * @var int      $envInProgress
 * @var int      $envStatus
 */
$environmentName = \sprintf(
    '%s%s%s',
    $this->allowUserManage ? "<a href='{$envPageUrl}'>" : '',
    $envName ?: \__('[Untitled environment]', 'statik-deployment'),
    $this->allowUserManage ? '</a>' : ''
);

?>
<span class="ab-icon ab-status <?= $envInProgress ? 'in-progress' : ''; ?>"
      data-color="<?= $this->getStatusColor($envStatus); ?>"></span>
<span class="env-name"><?= $environmentName; ?></span>
