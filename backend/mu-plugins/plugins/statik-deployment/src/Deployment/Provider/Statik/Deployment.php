<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Provider\Statik;

use Statik\Deploy\Deployment\Provider\Deployment\AbstractDeployment;
use Statik\Deploy\Deployment\Provider\Provider\ProviderInterface;

/**
 * Class Deployment.
 */
class Deployment extends AbstractDeployment
{
    private const previewPrefix = '/__sp/';

    /**
     * Generate a Deploy instance from statik history object.
     */
    public static function instance(array $raw, ProviderInterface $provider): self
    {
        $meta = \json_decode($raw['meta'] ?? '', true);

        return new self(
            id: $raw['id'],
            provider: $provider,
            name: $meta['name'] ?? null,
            startTime: \strtotime($raw['started_at'] ?? $raw['created_at']),
            endTime: isset($raw['finished_at']) ? \strtotime($raw['finished_at']) : null,
            stage: $raw['ready_state'],
            logUrl: $raw['log_url'] ?? null,
            isLive: (bool) $raw['live'],
            isPreview: (bool) ($raw['preview_id'] ?? null),
            previewPath: $meta['preview']['path'] ?? null,
            useCredentials: (bool) ($meta['user']['useCredentials'] ?? null),
            meta: \is_array($meta) ? $meta : [],
            raw: $raw,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(): ?string
    {
        $frontUrl = $this->provider->getFrontUrl();

        if (null === $frontUrl) {
            return null;
        }

        if ($this->isLive) {
            return $frontUrl;
        }

        $frontUrl = \sprintf('%s%s%s', \untrailingslashit($frontUrl), static::previewPrefix, "{$this->id}/");

        if ($this->isPreview) {
            return \sprintf('%s%s', \untrailingslashit($frontUrl), $this->previewPath ?? '/');
        }

        return $frontUrl;
    }
}
