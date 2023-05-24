<?php

declare(strict_types=1);

namespace Statik\Menu\Common\Settings\Field;

use Statik\Menu\Common\Config\ConfigInterface;
use Statik\Menu\Common\Settings\GeneratorInterface;

/**
 * Class AbstractField.
 */
abstract class AbstractField implements FieldInterface
{
    protected string $namespace;

    protected ?string $apiNamespace;

    protected ConfigInterface $config;

    protected string $label;

    protected string $description;

    protected ?array $properties;

    protected bool $isDefault;

    protected mixed $value;

    /** @var callable|null */
    protected $conditionsCallback;

    /** @var callable|null */
    protected $conditions;

    /**
     * AbstractField constructor.
     */
    public function __construct(protected string $name, array $structure, GeneratorInterface $generator)
    {
        $this->namespace = $generator->getNamespace();
        $this->apiNamespace = $generator->getApiNamespace();
        $this->config = $generator->getConfig();

        $this->value = $structure['value'] ?? null;
        $this->label = $structure['label'] ?? '';
        $this->description = $structure['description'] ?? '';
        $this->properties = $structure['attrs'] ?? null;
        $this->conditionsCallback = $structure['conditionsCallback'] ?? null;
        $this->conditions = $structure['conditions'] ?? null;
        $this->isDefault = $this->config::isDefaultSettings("{$this->name}.value")
            || ($structure['is_default'] ?? false);

        if ($this->isDefault) {
            $this->properties['disabled'] = 'disabled';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generateFieldsetHtml(): string
    {
        if (\is_callable($this->conditionsCallback) && false === ($this->conditionsCallback)($this)) {
            return '';
        }

        $conditions = \is_array($this->conditions)
            ? \esc_attr(\json_encode($this->conditions))
            : false;

        $class = match (static::class) {
            Repeater::class => 'repeater',
            Editor::class => 'editor',
            default => 'input',
        };

        \ob_start(); ?>

        <div class="statik-grid-row" <?= $conditions ?? false ? "data-conditions=\"{$conditions}\"" : ''; ?>>
            <div class="statik-grid-col">
                <label for="<?= "{$this->namespace}-{$this->name}"; ?>">
                    <?= $this->label; ?>
                    <?= isset($this->properties['required']) ? ' <sup>*</sup>' : null; ?>
                </label>
                <?php if ($this->description) { ?>
                    <span class="desc"><?= $this->description; ?></span>
                <?php } ?>
            </div>
            <div class="statik-grid-col <?= $class; ?>">
                <div><?= $this->generateFieldHtml(); ?></div>
                <?php if ($this->isDefault) { ?>
                    <i class="dashicons dashicons-admin-network"
                       title="<?= \__(
            'The value is locked by the constant and cannot be modified',
            'statik-commons'
        ); ?>"> </i>
                <?php } ?>
            </div>
        </div>

        <?php return \ob_get_clean();
    }

    /**
     * Generate field attributes based on provided data.ą.
     */
    protected function generateFieldAttributes(): string
    {
        $this->properties['id'] = $this->properties['name'] = null;

        $string = '';
        foreach ($this->properties as $key => $property) {
            if (empty($property)) {
                continue;
            }

            if (
                false === \in_array($key, static::NOT_DATA_ATTRS, true)
                && false === \str_starts_with($key, 'data-')
            ) {
                $key = "data-{$key}";
            }

            if (\is_array($property)) {
                $property = \implode(' ', $property);
            }

            $property = \esc_attr($property);

            $string .= "{$key}=\"{$property}\" ";
        }

        return $string;
    }
}
