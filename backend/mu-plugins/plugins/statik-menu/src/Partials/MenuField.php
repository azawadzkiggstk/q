<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\Menu\DI;

/**
 * @var WP_Post $navItem
 */
$navMenuId = $navItem->ID;

$selectedMegamenu = (int) \get_post_meta($navMenuId, 'megamenu-page', true);

?>
<p class="field-megamenu-page description description-wide submitbox" data-menu-item="<?= $navMenuId; ?>">
    <label for="megamenu-page-<?= $navMenuId; ?>">
        <span class="title">
            <strong><?= \__('Megamenu Page', 'statik-menu'); ?></strong>

            <span class="button-group">
                <a href="<?= \admin_url('post-new.php?post_type=megamenu'); ?>"
                   class="button button-small megamenu-new-link">
                    <?= \__('Add New', 'statik-menu'); ?></a>
                <a href="<?= \get_edit_post_link($selectedMegamenu); ?>"
                   target="_blank" class="button button-primary button-small megamenu-edit-link thickbox"
                   <?= \disabled(false === \current_user_can('edit_post', $selectedMegamenu)); ?>>
                    <?= \__('Edit', 'statik-menu'); ?>
                </a>
                <a href="<?= \get_delete_post_link($selectedMegamenu); ?>"
                   class="button button-remove button-small megamenu-remove-link"
                   onclick="return confirm('<?= \__('Are you sure do you want to remove this Megamenu?', 'statik-menu'); ?>')"
                   <?= \disabled(false === \current_user_can('delete_post', $selectedMegamenu)); ?>>
                    <?= \__('Remove', 'statik-menu'); ?>
                </a>
            </span>
        </span>

        <?php include DI::dir('src/Partials/MenuSelect.php'); ?>

        <span class="description">
            <?= \__('Select this field when menu item should support Megamenu', 'statik-menu'); ?>
        </span>
    </label>
</p>
