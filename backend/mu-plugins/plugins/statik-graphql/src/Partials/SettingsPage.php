<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\GraphQL\DI;

?>
<form method="POST">
    <?= \wp_nonce_field('statik_graphql_settings_nonce', '_graphql_nonce'); ?>
    <?= DI::Generator()->generateStructure('graphql_settings'); ?>
    <?= \get_submit_button('', 'primary', 'submit', false); ?>
</form>
