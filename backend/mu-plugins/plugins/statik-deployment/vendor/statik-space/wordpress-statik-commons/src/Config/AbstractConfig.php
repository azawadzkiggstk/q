<?php

declare(strict_types=1);

namespace Statik\Deploy\Common\Config;

use Illuminate\Support\Arr;
use Statik\Deploy\Common\Config\Driver\DriverInterface;
use Statik\Deploy\Common\Utils\ArrayUtils;
use Statik\Deploy\Common\Utils\Expirator;

/**
 * Class AbstractConfig.
 */
abstract class AbstractConfig implements ConfigInterface
{
    protected array $config;

    protected array $expirations;

    /** @var ConfigInterface[] */
    protected static array $instances = [];

    /**
     * AbstractConfig constructor.
     */
    public function __construct(protected DriverInterface $driver)
    {
        [$this->config, $this->expirations] = $this->driver->getFromSource();
    }

    /**
     * Get instance of a Config.
     */
    public static function Instance(string $namespace, DriverInterface $driver): ConfigInterface
    {
        if (false === isset(self::$instances[$namespace])) {
            self::$instances[$namespace] = new static($driver);
        }

        return self::$instances[$namespace];
    }

    /**
     * @return ConfigInterface[]
     */
    public static function getInstances(): array
    {
        return self::$instances;
    }

    /**
     * {@inheritdoc}
     */
    public function save(): bool
    {
        return $this->driver->setInSource($this->config, $this->expirations);
    }

    /**
     * {@inheritdoc}
     */
    public function flushExpirations(): bool
    {
        [$this->config, $this->expirations] = Expirator::filter($this->config, $this->expirations, true);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get(?string $offset, mixed $default = null): mixed
    {
        if (static::isDefaultSettings($offset)) {
            return Arr::get(
                \array_replace_recursive($this->config, static::getDefaultSettings()),
                $offset,
                $default
            );
        }

        return Arr::get($this->config, $offset, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(?string $offset, mixed $default = null): mixed
    {
        return $this->get("{$offset}.value", $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys(?string $offset): ?array
    {
        $values = $this->get($offset);

        return \is_array($values) ? \array_keys($this->get($offset)) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $offset, mixed $value, int $expiration = null): bool
    {
        if (static::isDefaultSettings($offset)) {
            return false;
        }

        $value = $this->filterData($value);

        if (\is_array($value)) {
            $value = ArrayUtils::deepDiff($value, static::getDefaultSettings());
        }

        Arr::set($this->config, $offset, $value);

        if (null !== $expiration && $expiration >= 0) {
            Arr::set($this->expirations, $offset, \time() + $expiration);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $offset): bool
    {
        if (static::isDefaultSettings($offset)) {
            return Arr::has(
                \array_replace_recursive($this->config, static::getDefaultSettings()),
                $offset
            );
        }

        return Arr::has($this->config, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $offset): bool
    {
        Arr::forget($this->config, $offset);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function last(string $offset, mixed $default = null): mixed
    {
        return Arr::last(Arr::get($this->config, $offset), null, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(string $offset, $value, bool $unique = false): bool
    {
        $values = Arr::prepend($this->get($offset, []), $value);

        return $this->set($offset, $unique ? \array_values(\array_unique($values, \SORT_REGULAR)) : $values);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(bool $flatten = false): array
    {
        $config = \array_replace_recursive($this->config, static::getDefaultSettings());

        return $flatten ? Arr::dot($config) : $config;
    }

    /**
     * Filter data from database and set correct variable type.
     */
    private function filterData(mixed $data): mixed
    {
        switch (\gettype($data)) {
            case 'boolean':
                $data = (bool) $data;
                break;
            case 'string':
                $data = \is_numeric($data) ? $data + 0 : \esc_html(\esc_attr((string) $data));
                break;
            case 'array':
                foreach ($data as $key => $item) {
                    $data[$key] = $this->filterData($item);
                }
        }

        return $data;
    }
}
