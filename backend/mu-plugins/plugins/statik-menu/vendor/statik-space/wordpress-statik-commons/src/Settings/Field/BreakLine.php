<?php

declare(strict_types=1);

namespace Statik\Menu\Common\Settings\Field;

/**
 * Class BreakLine.
 */
class BreakLine extends AbstractField
{
    /**
     * {@inheritDoc}
     */
    public function generateFieldsetHtml(): string
    {
        if (\is_callable($this->conditionsCallback) && false === ($this->conditionsCallback)($this)) {
            return '';
        }

        $conditions = \is_array($this->conditions) ? \esc_attr(\json_encode($this->conditions)) : false;

        \ob_start(); ?>

        <div class="statik-grid-row statik-one-column"
            <?= $conditions ? "data-conditions=\"{$conditions}\"" : ''; ?>>
            <div class="statik-grid-col">
                <div><?= $this->generateFieldHtml(); ?></div>
            </div>
        </div>

        <?php return \ob_get_clean();
    }

    /**
     * {@inheritdoc}
     */
    public function generateFieldHtml(): string
    {
        \ob_start(); ?>

        <hr/>

        <?php return \ob_get_clean();
    }
}
