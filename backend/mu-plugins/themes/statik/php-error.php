<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * This is a custom PHP error handler. Instead of displaying a full error
 * user should see a message, that the website is temporarily unavailable.
 */
$isDebug = WP_DEBUG && WP_DEBUG_DISPLAY;
$error = $isDebug ? \error_get_last() ?? null : null;
$error = $error ? "{$error['message']} in {$error['file']}" : null;

if (\headers_sent()) {
    \wp_die(
        $error ? null : 'There has been a critical error on this website.',
        null,
        ['response' => 500, 'exit' => true]
    );
}

\ob_get_clean(); // Prevent display regular error.
\http_response_code(500);

if (\defined('REST_REQUEST')) {
    \print_r(\json_encode([
        'code' => 'rest_internal_error',
        'message' => 'There has been a critical error on this website.',
        'data' => ['status' => 500, 'error' => $error ?: null],
    ]));

    return;
}

if (\wp_doing_ajax() || \wp_doing_cron() || \headers_sent()) {
    \print_r($error ?: 'There has been a critical error on this website.');

    return;
}

\define('STATIK_ERROR_PAGE', true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website is temporary unavailable - Statik</title>
    <link rel="shortcut icon"
          href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg'><text y='32' font-size='32'>⛔️</text></svg>">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php require __DIR__ . '/layout/stylesheets.php'; ?>
</head>

<body>
<div class="wrapper">
    <main>
        <header><?php require __DIR__ . '/layout/logo.php'; ?></header>
        <h1>Error <strong>500</strong></h1>
        <p>There was a problem with handling your request. Please come back in a minute.</p>

        <?php if ($error) { ?>
            <h2>Error message:</h2>
            <code><?= $error; ?></code>
        <?php } ?>
    </main>
    <footer><p>Powered by Statik Platform <?= \date('Y'); ?></p></footer>
</div>
</body>
</html>
