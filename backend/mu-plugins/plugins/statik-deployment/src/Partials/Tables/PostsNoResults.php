<?php

declare(strict_types=1);

use Statik\Deploy\Dashboard\Table\HistoryTable;

\defined('WPINC') || exit;

/**
 * @var HistoryTable $this
 */
?>

<div class="empty-page" style="height: 100px;">
    <h2 style="margin:0 0 20px;"><?= \__('There is nothing here yet', 'statik-deployment'); ?></h2>
    <p style="margin:0;">
        <?= \__(
    'If anyone makes changes to any of the supported CPTs, that information will be displayed here.',
    'statik-deployment'
); ?>
    </p>
</div>
