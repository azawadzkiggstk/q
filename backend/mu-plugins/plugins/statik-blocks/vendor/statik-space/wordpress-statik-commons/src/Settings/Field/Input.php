<?php

declare(strict_types=1);

namespace Statik\Blocks\Common\Settings\Field;

use Statik\Blocks\Common\Settings\GeneratorInterface;

/**
 * Class Input.
 */
class Input extends AbstractField
{
    public const IS_REDACTED = '** REDACTED **';

    /**
     * Input constructor.
     */
    public function __construct(string $name, array $structure, GeneratorInterface $generator)
    {
        parent::__construct($name, $structure, $generator);

        if (false === isset($this->properties['type'])) {
            $this->properties['type'] = 'text';
        }

        if ($this->value && 'password' === $this->properties['type']) {
            $this->properties['type'] = 'text';
            $this->properties['required'] = false;
            $this->properties['disabled'] = 'disabled';
            $this->value = self::IS_REDACTED . ($this->isDefault ? '' : \__(' (click to update)', 'statik-commons'));

            if (false === $this->isDefault) {
                $this->properties['class'] ??= '';
                $this->properties['class'] .= ' statik-is-password';
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generateFieldHtml(): string
    {
        return $this->getFieldHtml();
    }

    /**
     * Generate field HTML.
     */
    private function getFieldHtml(): string
    {
        \ob_start(); ?>

        <input id="<?= "{$this->namespace}-{$this->name}"; ?>"
               name="<?= "{$this->namespace}[{$this->name}.value]"; ?>"
               value="<?= \filter_var($this->value); ?>"
            <?= $this->generateFieldAttributes(); ?>>

        <?php return \ob_get_clean();
    }
}
