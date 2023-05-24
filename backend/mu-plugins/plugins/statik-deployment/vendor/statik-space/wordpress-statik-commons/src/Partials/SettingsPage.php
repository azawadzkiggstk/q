<?php

declare(strict_types=1);

\defined('WPINC') || exit;

/**
 * Fire on settings page filter.
 *
 * @param array $tabs current tabs
 *
 * @return array
 */
$tabs = (array) \apply_filters('Statik\Common\settingsTabs', []);

$currentTab = \filter_input(\INPUT_GET, 'tab');
$currentTab = \array_key_exists($currentTab, $tabs) ? $currentTab : \key($tabs);

$adminUrl = \add_query_arg(['page' => 'statik_settings'], \admin_url('admin.php'));

?>
<div id="statik" class="wrap">
    <h1><?= $GLOBALS['title']; ?></h1>

    <hr/>

    <div class="nav-tab-wrapper settings-tabs">
        <?php foreach ($tabs as $tab => $name) { ?>
            <a href="<?= \add_query_arg('tab', $tab, $adminUrl); ?>"
               class="nav-tab <?= $tab === $currentTab ? 'nav-tab-active' : null; ?>">
                <?= $name; ?>
            </a>
        <?php } ?>

        <label>
            <select id="nav-select">
                <?php foreach ($tabs as $tab => $name) { ?>
                    <option value="<?= \add_query_arg('tab', $tab); ?>" <?php \selected($tab === $currentTab); ?>>
                        <?= $name; ?>
                    </option>
                <?php } ?>
            </select>
        </label>
    </div>

    <?php
    /**
     * Fire the settings page tab filter.
     *
     * @param string $currentTab current tab
     */
    \do_action('Statik\Common\settingsPageTabs', $currentTab);
?>
</div>
