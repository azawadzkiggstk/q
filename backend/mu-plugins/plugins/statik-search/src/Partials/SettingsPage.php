<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\Search\DI;

?>
<form method="POST">
    <?= \wp_nonce_field('statik_search_settings_nonce', '_search_nonce'); ?>
    <?= DI::Generator()->generateStructure('search_settings'); ?>
    <?= \get_submit_button('', 'primary', 'submit', false); ?>
</form>
