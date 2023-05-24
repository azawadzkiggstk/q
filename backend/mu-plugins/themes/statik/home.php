<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * Default template for all WordPress native pages. In a headless
 * CMS approach, no content should be rendered via the theme, so
 * we will display just a landing page.
 */
$userLoggedIn = \is_user_logged_in();
$navigateDashboard = \sprintf(
    \__('Navigate to the %s and log in.', 'statik-luna'),
    '<a href="' . \wp_login_url() . '">' . \__('Dashboard', 'statik-luna') . '</a>'
);

?>
<!DOCTYPE html>
<html <?php \language_attributes(); ?>>
<head>
    <meta charset="<?php \bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \get_bloginfo('name'); ?> - Statik</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300&display=swap"
          rel="preload" as="style" onload="this.rel='stylesheet'">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php require __DIR__ . '/layout/stylesheets.php'; ?>

    <?php $userLoggedIn && \wp_head(); ?>
</head>

<body>
<div class="wrapper">
    <main>
        <header><?php require __DIR__ . '/layout/logo.php'; ?></header>
        <h1 title="<?= \get_bloginfo('name'); ?>">
            <?= \__('Welcome to', 'statik-luna'); ?><br/>
            <strong><?= \wp_trim_words(\get_bloginfo('name'), 8); ?></strong>
        </h1>

        <?php if ($userLoggedIn) { ?>
            <p><?= \__('Navigate to one of the following pages or use the Admin Bar above.', 'statik-luna'); ?></p>
            <div class="buttons">
                <a href="<?= \admin_url(); ?>">
                    <span class="dashicons dashicons-dashboard"></span> <?= \__('Dashboard', 'statik-luna'); ?>
                </a>
                <a href="<?= \get_edit_post_link(); ?>">
                    <span class="dashicons dashicons-edit"></span> <?= \__('Edit Home Page', 'statik-luna'); ?>
                </a>
                <a href="<?= \admin_url(\add_query_arg('page', 'statik_global_settings', 'admin.php')); ?>">
                    <span class="dashicons dashicons-admin-settings"></span>
                    <?= \__('Global Settings', 'statik-luna'); ?>
                </a>
                <a href="<?= \admin_url(\add_query_arg('page', 'statik_deployment', 'admin.php')); ?>">
                    <span class="dashicons dashicons-megaphone"></span> <?= \__('Deploy Website', 'statik-luna'); ?>
                </a>
            </div>
        <?php } else { ?>
            <p><?= $navigateDashboard; ?></p>
        <?php } ?>

    </main>
    <footer><p><?= \__('Powered by Statik Platform', 'statik-luna'); ?> <?= \date('Y'); ?></p></footer>
</div>

<?php $userLoggedIn && \wp_footer(); ?>
</body>
</html>
