<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Provider\Deployment;

/**
 * Interface DeploymentInterface.
 */
interface DeploymentInterface
{
    public const READY = 'READY';
    public const IN_PROGRESS = 'IN_PROGRESS';
    public const ERROR = 'ERROR';

    /**
     * Get deployment ID.
     */
    public function getId(): string;

    /**
     * Get deployment name.
     */
    public function getName(): ?string;

    /**
     * Get deployment start time.
     */
    public function getStartTime(): ?int;

    /**
     * Get deployment end time.
     */
    public function getEndTime(): ?int;

    /**
     * Get deployment stage.
     */
    public function getStage(): ?string;

    /**
     * Get deployment log URL.
     */
    public function getLogUrl(): ?string;

    /**
     * Whether deployment is live.
     */
    public function isLive(): bool;

    /**
     * Whether deployment is preview.
     */
    public function isPreview(): bool;

    /**
     * Get preview page path.
     */
    public function getPreviewPath(): ?string;

    /**
     * Whether deployment use credentials.
     */
    public function useCredentials(): bool;

    /**
     * Get stage durations.
     */
    public function getStageTimes(): array;

    /**
     * Get deployment raw response.
     */
    public function getRaw(string $key = null): mixed;

    /**
     * Get deployment meta value.
     */
    public function getMeta(string $key = null): mixed;

    /**
     * Get deployment URL.
     */
    public function getUrl(): ?string;

    /**
     * Get deployment status.
     */
    public function getStatus(): ?string;

    /**
     * Whether deploy has status.
     */
    public function hasStatus(string $status): bool;

    /**
     * Get deployment as an array.
     */
    public function toArray(): array;
}
