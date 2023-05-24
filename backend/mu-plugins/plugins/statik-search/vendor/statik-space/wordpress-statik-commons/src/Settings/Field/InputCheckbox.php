<?php

declare(strict_types=1);

namespace Statik\Search\Common\Settings\Field;

use Statik\Search\Common\Settings\GeneratorInterface;
use Statik\Search\Common\Utils\Callback;

/**
 * Class InputCheckbox.
 */
class InputCheckbox extends AbstractField
{
    /** @var array|callable */
    private $availableValues;

    /**
     * InputCheckbox constructor.
     */
    public function __construct(string $name, array $structure, GeneratorInterface $generator)
    {
        parent::__construct($name, $structure, $generator);

        $this->availableValues = $structure['values'] ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function generateFieldHtml(): string
    {
        $values = Callback::getResults($this->availableValues);
        $hasManyValues = 1 !== \count($values);

        $field = \sprintf(
            '<input type="hidden" name="%s[%s.value]" %s>',
            $this->namespace,
            $this->name,
            \disabled((bool) ($this->properties['disabled'] ?? false), true, false)
        );

        foreach ($values as $key => $option) {
            $field .= $this->getFieldHtml($key, $option, $hasManyValues);
        }

        return $field;
    }

    /**
     * Generate field HTML.
     */
    private function getFieldHtml(mixed $key, mixed $option, bool $hasMany = true): string
    {
        $checked = \is_array($this->value) ? \in_array($key, $this->value) ?? false : false === empty($this->value);

        \ob_start(); ?>

        <label for="<?= "{$this->namespace}-{$this->name}" . ($hasMany ? "-{$key}" : ''); ?>">
            <input type="checkbox" value="<?= $key; ?>"
                   name="<?= "{$this->namespace}[{$this->name}.value]" . ($hasMany ? '[]' : ''); ?>"
                   id="<?= "{$this->namespace}-{$this->name}" . ($hasMany ? "-{$key}" : ''); ?>"
                <?= \checked($checked); ?> <?= $this->generateFieldAttributes(); ?>>
            <?= $option; ?>
        </label>

        <?php return \ob_get_clean();
    }
}
