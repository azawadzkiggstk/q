<?php

declare(strict_types=1);

namespace Statik\Menu\Common\Cli\Command;

use Statik\Menu\Common\Cli\CommandManagerInterface;

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
