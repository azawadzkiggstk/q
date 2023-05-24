<?php

declare(strict_types=1);

namespace Statik\Search\Common\Rest\Controller;

use Statik\Search\Common\Config\ConfigInterface;
use Statik\Search\Common\Rest\ApiInterface;

/**
 * Class AbstractController.
 */
abstract class AbstractController extends \WP_REST_Controller implements ControllerInterface
{
    protected ConfigInterface $config;

    /**
     * AbstractController constructor.
     */
    public function __construct(protected ApiInterface $api)
    {
    }

    /**
     * Check if current user has correct capabilities.
     */
    public function checkPermissions(): bool
    {
        return \current_user_can('manage_options');
    }

    /**
     * Check if parameter is type of numeric.
     */
    public function isNumeric(mixed $param): bool
    {
        return \is_numeric($param);
    }
}
