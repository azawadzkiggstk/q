<?php

declare(strict_types=1);

namespace Statik\GraphQL\Common\Cli\Command;

use Statik\GraphQL\Common\Cli\CommandManagerInterface;

/**
 * Class AbstractCommand.
 */
abstract class AbstractCommand implements CommandInterface
{
    /**
     * AbstractCommand constructor.
     */
    public function __construct(protected ?CommandManagerInterface $commandManager = null)
    {
    }
}
