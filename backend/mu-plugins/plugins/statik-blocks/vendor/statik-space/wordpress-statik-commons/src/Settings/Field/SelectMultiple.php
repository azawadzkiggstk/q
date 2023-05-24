<?php

declare(strict_types=1);

namespace Statik\Blocks\Common\Settings\Field;

use Statik\Blocks\Common\Settings\GeneratorInterface;
use Statik\Blocks\Common\Utils\Callback;

/**
 * Class SelectMultiple.
 */
class SelectMultiple extends AbstractField
{
    /** @var array|callable */
    private $availableValues;

    /**
     * Select constructor.
     */
    public function __construct(string $name, array $structure, GeneratorInterface $generator)
    {
        parent::__construct($name, $structure, $generator);

        $this->availableValues = $structure['values'] ?? [];

        $this->value = (array) $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function generateFieldHtml(): string
    {
        $values = Callback::getResults($this->availableValues);
        $options = '';

        if ($this->properties['create'] ?? false) {
            $values = \array_merge($values, $this->value);
        }

        $before = \sprintf(
            '<input type="hidden" name="%s[%s.value]" %s>',
            $this->namespace,
            $this->name,
            \disabled((bool) ($this->properties['disabled'] ?? false), true, false)
        );

        foreach ($values as $option) {
            $selected = \selected(\in_array($option, $this->value, true), true, false);
            $options .= "<option value=\"{$option}\" {$selected}>{$option}</option>";
        }

        return $before . $this->getFieldHtml($options);
    }

    /**
     * Generate field HTML.
     */
    private function getFieldHtml(string $options): string
    {
        \ob_start(); ?>

        <select id="<?= "{$this->namespace}-{$this->name}"; ?>"
                name="<?= "{$this->namespace}[{$this->name}.value][]"; ?>"
                data-multiple="true" multiple="multiple" <?= $this->generateFieldAttributes(); ?>>
            <?= $options; ?>
        </select>

        <?php return \ob_get_clean();
    }
}
