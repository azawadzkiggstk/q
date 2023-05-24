<?php

declare(strict_types=1);

use Statik\Deploy\Deployment\Error\DeploymentException;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\DI;

\defined('WPINC') || exit;

/**
 * @var string                   $environment
 * @var DeploymentInterface|null $deployment
 * @var DeploymentException|null $exception
 */
$historyUrl = \add_query_arg([
    'page' => 'statik_deployment_history',
    'env' => $environment,
    'preview' => 1,
], \admin_url('admin.php'));

$inProgress = $deployment && $deployment->hasStatus(DeploymentInterface::IN_PROGRESS);
$ready = $deployment && $deployment->hasStatus(DeploymentInterface::READY);
$error = $exception ?? $deployment?->hasStatus(DeploymentInterface::ERROR);
$title = $inProgress ? \__('Generating preview...', 'statik-deployment') : \__('Preview error', 'statik-deployment');

if ($ready && $deployment->getUrl()) {
    \wp_redirect($deployment->getUrl());

    exit;
}

$deployStatus = \__('The deployment is in <strong>%s</strong> status.<br/> You will be redirected to the'
    . ' preview page as soon as it is ready.', 'statik-deployment');

?>
<!DOCTYPE html>
<html <?php \language_attributes(); ?>>
<head>
    <meta charset="<?php \bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300&display=swap"
          rel="preload" as="style" onload="this.rel='stylesheet'">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php include __DIR__ . '/layout/stylesheets.php'; ?>

    <?php \wp_head(); ?>
</head>

<body>
<div class="wrapper">
    <main>
        <header>
            <img alt="Statik logo" src="<?= DI::url('/assets/images/statik.svg'); ?>">
        </header>
        <?php if ($inProgress) { ?>
            <h1><?= \__('Generating preview', 'statik-deployment'); ?><span class="wait">...</span></h1>

            <p><?= \sprintf($deployStatus, $deployment->getStage()); ?></p>
            <script>
              setTimeout(() => window.location.reload(1), 5000);
              window.setInterval(() => {
                const waits = document.getElementsByClassName('wait');
                for (const wait of waits) {
                  wait.innerHTML = wait.innerHTML.length >= 5 ? '' : wait.innerHTML + '.';
                }
              }, 1000);
            </script>
        <?php } ?>
        <?php if ($error) { ?>
            <?php $isException = $error instanceof Exception; ?>
            <h1>
                <?= \__('Error', 'statik-deployment'); ?>
                <strong><?= $isException ? $error->getCode() : 500; ?></strong>
            </h1>
            <p><?= \__('The preview build could not be completed due to an error. Please try again or contact'
                    . ' the website administrator.', 'statik-deployment'); ?></p>
            <p class="message error">
                <?= $isException
                    ? $error->getMessage()
                    : \__('There was an error in the deployment process.', 'statik-deployment'); ?>
            </p>
            <div class="buttons">
                <a href="<?= $historyUrl; ?>">
                    <span class="dashicons dashicons-text-page"></span> <?= \__('View details', 'statik-deployment'); ?>
                </a>
            </div>
        <?php } ?>

    </main>
    <footer><p><?= \__('Powered by Statik Platform', 'statik-deployment'); ?> <?= \date('Y'); ?></p></footer>
</div>

<?php \wp_footer(); ?>
</body>
</html>

