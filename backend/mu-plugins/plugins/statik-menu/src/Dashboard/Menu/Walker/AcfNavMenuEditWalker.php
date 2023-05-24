<?php

declare(strict_types=1);

namespace Statik\Menu\Dashboard\Menu\Walker;

use Statik\Menu\DI;

/**
 * Class AcfNavMenuEditWalker.
 */
class AcfNavMenuEditWalker extends \ACF_Walker_Nav_Menu_Edit
{
    /**
     * {@inheritdoc}
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0): void
    {
        $elOutput = '';
        parent::start_el($elOutput, $item, $depth, $args, $id);

        $output .= \preg_replace(
            '/(?=<(fieldset|p)[^>]+class="[^"]*field-move)/',
            $this->getFields($item),
            $elOutput
        );
    }

    /**
     * Generate fields HTML.
     */
    private function getFields(\WP_Post $navItem): string
    {
        \ob_start();
        require DI::dir('src/Partials/MenuField.php');

        return \ob_get_clean();
    }
}
