<?php

declare(strict_types=1);

use Statik\Deploy\Dashboard\Table\HistoryTable;

\defined('WPINC') || exit;

/**
 * @var HistoryTable $this
 */
?>

<div class="empty-page" style="height: 100px;">
    <?php if ($this->error) { ?>
        <h2 style="margin:0 0 20px;"><?= \__('There was an error', 'statik-deployment'); ?></h2>
        <code><?= $this->error; ?></code>
    <?php } else { ?>
        <h2 style="margin:0 0 20px;"><?= \__('There is nothing here yet', 'statik-deployment'); ?></h2>
        <p style="margin:0;">
            <?= \__(
    'A list of all implementations made for this environment will be displayed here.',
    'statik-deployment'
); ?>
        </p>
    <?php } ?>
</div>
