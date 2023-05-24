<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * This is a custom maintenance page. This page is displaying when the WordPress
 * maintenance mode is enabled - for example when plugins are updating.
 */
\header('Refresh: 6');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website is under maintenance - Statik</title>
    <link rel="shortcut icon"
          href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg'><text y='32' font-size='32'>⚠️</text></svg>">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php require __DIR__ . '/layout/stylesheets.php'; ?>
</head>

<body>
<div class="wrapper">
    <main>
        <header><?php require __DIR__ . '/layout/logo.php'; ?></header>
        <h1>Website under <strong>maintenance</strong></h1>
        <p>The website is temporarily unavailable because of maintenance works. Please come back in a minute.</p>
        <p>Refresh in <span id="counter">5</span> seconds...</p>
    </main>
    <footer><p>Powered by Statik Platform <?= \date('Y'); ?></p></footer>
</div>

<script type="text/javascript">
  let time = +document.getElementById('counter').textContent;
  const timer = setInterval(() => {
    document.getElementById('counter').textContent = --time;
    if (time <= 0) clearInterval(timer);
  }, 1000);
</script>
</body>
</html>
