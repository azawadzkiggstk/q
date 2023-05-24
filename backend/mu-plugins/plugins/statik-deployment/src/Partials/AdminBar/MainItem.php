<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\Deploy\Dashboard\AdminBar;

/**
 * @var AdminBar $this
 * @var int      $status
 * @var int      $inProgress
 */
?>
<i class="ab-icon deploy"> </i>
<?= \__('Deployment', 'statik-deployment'); ?>
<i class="ab-icon ab-status js-global-status <?= $inProgress ? 'in-progress' : ''; ?>"
   data-color="<?= $this->getStatusColor($status); ?>"> </i>
