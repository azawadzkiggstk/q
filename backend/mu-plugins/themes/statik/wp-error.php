<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * This is a custom WP error handler. Instead of displaying a full error
 * user should see a message, that the website is temporarily unavailable.
 *
 * @var WP_Error|string $message
 * @var string          $title
 * @var array|string    $args
 */
[$message, $title, $parsedArgs] = \_wp_die_process_input($message, $title, $args);

if (\is_string($message)) {
    if (false === empty($parsedArgs['additional_errors'])) {
        $message = \array_merge([$message], \wp_list_pluck($parsedArgs['additional_errors'], 'message'));
        $message = "<ul>\n\t\t<li>" . \implode("</li>\n\t\t<li>", $message) . "</li>\n\t</ul>";
    }

    $message = \sprintf('<div class="wp-die-message">%s</div>', $message);
}

$haveGettext = \function_exists('__');

if (false === empty($parsedArgs['link_url']) && false === empty($parsedArgs['link_text'])) {
    $linkUrl = $parsedArgs['link_url'];
    if (\function_exists('esc_url')) {
        $linkUrl = \esc_url($linkUrl);
    }

    $linkText = $parsedArgs['link_text'];
    $message .= "\n<p><a href='{$linkUrl}'>{$linkText}</a></p>";
}

if (isset($parsedArgs['back_link']) && $parsedArgs['back_link']) {
    $backText = $haveGettext ? \__('&laquo; Back', 'statik') : '&laquo; Back';
    $message .= "\n<p><a href='javascript:history.back()'>{$backText}</a></p>";
}

if (0 === \did_action('admin_head')) {
    if (false === \headers_sent()) {
        \header("Content-Type: text/html; charset={$parsedArgs['charset']}");
        \status_header($parsedArgs['response']);
        \nocache_headers();
    }
} else {
    return \print_r($message ?: 'There has been a critical error on this website.');
}

\define('STATIK_ERROR_PAGE', true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unexpected action - Statik</title>
    <link rel="shortcut icon"
          href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg'><text y='32' font-size='32'>⚠️</text></svg>">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php require __DIR__ . '/layout/stylesheets.php'; ?>
</head>

<body>
<div class="wrapper">
    <main>
        <header><?php require __DIR__ . '/layout/logo.php'; ?></header>
        <h1>Error <strong><?= $args['response'] ?? 400; ?></strong></h1>
        <?= $message
            ? \str_replace(['<h1>', '</h1>', '<p>', '</p>'], ['<h2>', '</h2>', '', ''], (string) $message)
            : null; ?>
    </main>
    <footer><p>Powered by Statik Platform <?= \date('Y'); ?></p></footer>
</div>
</body>
</html>
