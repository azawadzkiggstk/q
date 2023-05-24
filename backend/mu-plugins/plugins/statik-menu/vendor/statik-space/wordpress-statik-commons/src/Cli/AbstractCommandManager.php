<?php

declare(strict_types=1);

namespace Statik\Menu\Common\Cli;

use Statik\Menu\Common\Cli\Command\CommandInterface;
use Statik\Menu\Common\Cli\Command\ConfigCommand;

/**
 * Class AbstractCommandManager.
 */
abstract class AbstractCommandManager implements CommandManagerInterface
{
    /** @var CommandInterface[] */
    protected array $registeredCommands = [];

    /**
     * AbstractCommandManager constructor.
     */
    public function __construct(protected string $commandName)
    {
        if (false === \defined('WP_CLI') || false === WP_CLI) {
            return;
        }

        $this->registerCommand(ConfigCommand::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * {@inheritdoc}
     *
     * Allows register only command that implements CommandInterface.
     */
    public function registerCommand(string $commandClass): CommandManagerInterface
    {
        if (
            false === \array_key_exists($commandClass, $this->registeredCommands)
            && \is_a($commandClass, CommandInterface::class, true)
        ) {
            /** @var CommandInterface $command */
            $command = new $commandClass($this);

            $this->registeredCommands[$commandClass] = $command;
        }

        return $this;
    }
}
