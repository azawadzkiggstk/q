<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\Menu\Dashboard\Cpt\MegamenuCpt;

/**
 * @var int $navMenuId navigation ID
 */
$megaMenus = \get_posts(['post_type' => MegamenuCpt::CPT_SLUG, 'posts_per_page' => -1, 'fields' => 'ids']);
$selectedMegamenu = (int) \get_post_meta($navMenuId, 'megamenu-page', true);

if (0 !== $selectedMegamenu && false === \in_array($selectedMegamenu, $megaMenus, true)) {
    $selectedMegamenu = 0;
    \update_post_meta($navMenuId, 'megamenu-page', $selectedMegamenu);
}
?>
<select id="megamenu-page-<?= $navMenuId; ?>" type="text" class="widefat"
        name="<?= "menu-item-statik_menu[{$navMenuId}][megamenu-page]"; ?>">
    <option value="" data-edit-link="" data-remove-link="">--- <?= \__('No Megamenu', 'statik-menu'); ?> ---</option>
    <?php foreach ($megaMenus as $megaMenu) { ?>
        <option value="<?= $megaMenu; ?>" <?= \selected($megaMenu, $selectedMegamenu); ?>
                data-edit-link="<?= \get_edit_post_link($megaMenu); ?>"
                data-remove-link="<?= \get_delete_post_link($megaMenu); ?>">
            <?= \get_the_title($megaMenu); ?>
        </option>
    <?php } ?>
</select>
