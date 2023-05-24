<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\Deploy\DI;

?>
<form method="POST">
    <?php \wp_nonce_field('statik_deployment_settings_nonce', '_deploy_nonce'); ?>
    <?= DI::Generator()->generateStructure('settings'); ?>
    <?= \get_submit_button('', 'primary', 'submit', false); ?>
</form>