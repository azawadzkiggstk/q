<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Provider\Statik;

use Statik\Deploy\Deployment\Provider\Provider\AbstractProvider;
use Statik\Deploy\DI;

/**
 * Class Provider.
 */
class Provider extends AbstractProvider
{
    public const PROVIDER_NAME = 'Statik';

    /**
     * {@inheritdoc}
     */
    public function __construct(?string $environment)
    {
        parent::__construct($environment, new Api($this), new Settings($this));
    }

    /**
     * {@inheritDoc}
     */
    public function getFrontUrl(): ?string
    {
        $front = DI::Config()->get("env.{$this->environment}.values.statik.frontend_url.value");
        $prefix = $this->settings->getSitePrefix();

        return $front ? "https://{$front}{$prefix}" : null;
    }
}
