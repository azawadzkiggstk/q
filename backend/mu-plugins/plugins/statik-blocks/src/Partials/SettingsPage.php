<?php

declare(strict_types=1);

use Statik\Blocks\Dashboard\Page\SettingsPage;
use Statik\Blocks\DI;

/** @var SettingsPage $this */
?>
<form method="POST">
    <?= \wp_nonce_field('statik_blocks_settings_nonce', '_blocks_nonce'); ?>
    <?= DI::Generator()->generateStructure('blocks_settings'); ?>
    <?= \get_submit_button('', 'primary', 'submit', false); ?>
</form>