<?php

declare(strict_types=1);

namespace Statik\Search\Common\Config\Driver;

/**
 * Class NetworkDatabaseDriver.
 *
 * Driver using the WordPress database as a source.
 */
class NetworkDatabaseDriver extends DatabaseDriver
{
    protected string $setFn = 'update_site_option';
    protected string $getFn = 'get_site_option';
}
