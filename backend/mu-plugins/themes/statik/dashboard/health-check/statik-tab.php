<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

global $statik;

$activePlugins = \array_filter(
    $statik['protected_plugins'] ?? [],
    static fn (array $plugin): bool => $plugin['Enabled']
);
$inactivePlugins = \array_filter(
    $statik['protected_plugins'] ?? [],
    static fn (array $plugin): bool => false === $plugin['Enabled']
);

?>
<div class="notice notice-error hide-if-js">
    <p><?= \__('The Site Health check requires JavaScript.'); ?></p>
</div>
<div class="health-check-body health-check-debug-tab hide-if-no-js">
    <h2><?= \__('Statik Infrastructure Info', 'statik-luna'); ?></h2>

    <div id="health-check-debug" class="health-check-accordion">
        <h3 class="health-check-accordion-heading">
            <button aria-expanded="false" class="health-check-accordion-trigger"
                    aria-controls="health-check-accordion-block-active-plugins" type="button">
                <span class="title">
                    <?= \sprintf('%s (%d)', \__('Active protected plugins', 'statik-luna'), \count($activePlugins)); ?>
                </span>
                <span class="icon"></span>
            </button>
        </h3>
        <div id="health-check-accordion-block-active-plugins" class="health-check-accordion-panel" hidden="hidden">
            <table class="widefat striped health-check-table" role="presentation">
                <tbody>
                <?php foreach ($activePlugins as $pl) { ?>
                    <tr>
                        <td><?= $pl['Name']; ?></td>
                        <td>
                            <?= \sprintf(\__('Version %s by %s', 'statik-luna'), $pl['Version'], $pl['AuthorName']); ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="health-check-debug" class="health-check-accordion">
        <h3 class="health-check-accordion-heading">
            <button aria-expanded="false" class="health-check-accordion-trigger"
                    aria-controls="health-check-accordion-block-inactive-plugins" type="button">
                <span class="title">
                    <?= \sprintf('%s (%d)', \__('Inactive protected plugins', 'statik-luna'), \count($inactivePlugins)); ?>
                </span>
                <span class="icon"></span>
            </button>
        </h3>
        <div id="health-check-accordion-block-inactive-plugins" class="health-check-accordion-panel" hidden="hidden">
            <table class="widefat striped health-check-table" role="presentation">
                <tbody>
                <p>
                    <?= \__('Protected plugins are disabled when the same regular plugins are enabled.', 'statik-luna'); ?>
                </p>
                <?php foreach ($inactivePlugins as $pl) { ?>
                    <tr>
                        <td><?= $pl['Name']; ?></td>
                        <td>
                            <?= \sprintf(\__('Version %s by %s', 'statik-luna'), $pl['Version'], $pl['AuthorName']); ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>