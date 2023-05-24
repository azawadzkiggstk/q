<?php

declare(strict_types=1);

namespace Statik\Blocks\Common\Settings;

use Statik\Blocks\Common\Config\ConfigInterface;

/**
 * Interface GeneratorInterface.
 */
interface GeneratorInterface
{
    /**
     * Generate HTML structure.
     */
    public function generateStructure(string $group): string;

    /**
     * Get namespace.
     */
    public function getNamespace(): string;

    /**
     * Get API namespace.
     */
    public function getApiNamespace(): ?string;

    /**
     * Get Config instance.
     */
    public function getConfig(): ConfigInterface;

    /**
     * Initialize fields with values.
     */
    public function registerFields(string $key, array $fields): ?array;
}
