<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Provider\Deployment;

use Illuminate\Support\Arr;
use Statik\Deploy\Deployment\Provider\Provider\AbstractProvider;
use Statik\Deploy\DI;

/**
 * Class AbstractDeployment.
 */
abstract class AbstractDeployment implements DeploymentInterface
{
    /**
     * AbstractDeploy constructor.
     */
    public function __construct(
        protected string $id,
        protected AbstractProvider $provider,
        protected ?string $name = null,
        protected ?int $startTime = null,
        protected ?int $endTime = null,
        protected ?string $stage = null,
        protected ?string $logUrl = null,
        protected bool $isLive = false,
        protected bool $isPreview = false,
        protected ?string $previewPath = null,
        protected bool $useCredentials = false,
        protected array $meta = [],
        protected array $raw = [],
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getStartTime(): ?int
    {
        return $this->startTime;
    }

    /**
     * {@inheritDoc}
     */
    public function getEndTime(): ?int
    {
        return $this->endTime;
    }

    /**
     * {@inheritDoc}
     */
    public function getStage(): ?string
    {
        return $this->stage;
    }

    /**
     * {@inheritDoc}
     */
    public function getLogUrl(): ?string
    {
        return $this->logUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function isLive(): bool
    {
        return $this->isLive;
    }

    /**
     * {@inheritDoc}
     */
    public function isPreview(): bool
    {
        return $this->isPreview;
    }

    /**
     * {@inheritDoc}
     */
    public function getPreviewPath(): ?string
    {
        return $this->isPreview ? ($this->previewPath ?? '/') : null;
    }

    /**
     * {@inheritDoc}
     */
    public function useCredentials(): bool
    {
        return $this->useCredentials;
    }

    /**
     * {@inheritDoc}
     */
    public function getRaw(string $key = null): mixed
    {
        return $key ? Arr::get($this->raw ?? [], $key) : $this->raw;
    }

    /**
     * {@inheritDoc}
     */
    public function getMeta(string $key = null): mixed
    {
        return $key ? Arr::get($this->meta ?? [], $key) : $this->meta;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): ?string
    {
        return match ($this->stage) {
            'READY' => static::READY,
            'ERROR' => static::ERROR,
            default => static::IN_PROGRESS,
        };
    }

    /**
     * {@inheritDoc}
     */
    public function hasStatus(string $status): bool
    {
        return $this->getStatus() === $status;
    }

    /**
     * {@inheritDoc}
     */
    public function getStageTimes(): array
    {
        $cacheKey = "env.{$this->provider->getEnvironment()}.deployment.{$this->getId()}";
        $stages = DI::Config()->get($cacheKey, []);
        $stages = \array_merge([$this->getStage() => \time()], $stages);

        DI::Config()->set($cacheKey, $stages);
        DI::Config()->save();

        return $this->getStageDurations($stages);
    }

    /**
     * Get a duration of each step based on the steps start time.
     */
    private function getStageDurations(array $stages): array
    {
        foreach (\array_keys($stages) as $step) {
            if (false === isset($stages[$step])) {
                $stepsDurations[$step] = null;
                continue;
            }

            $biggerTime = \array_filter($stages, static fn ($n) => $n > $stages[$step]);

            \sort($biggerTime);

            $stepsDurations[$step] = [
                $stages[$step],
                \count($biggerTime) ? \reset($biggerTime) : \time(),
            ];
        }

        return $stepsDurations ?? [];
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(): ?string
    {
        return $this->provider->getFrontUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return \array_merge(
            \call_user_func('get_object_vars', $this),
            [
                'status' => $this->getStatus(),
                'stageTimes' => $this->getStageTimes(),
                'url' => $this->getUrl(),
            ]
        );
    }
}
