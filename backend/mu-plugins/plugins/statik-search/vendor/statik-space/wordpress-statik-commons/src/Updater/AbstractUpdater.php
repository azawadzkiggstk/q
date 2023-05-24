<?php

declare(strict_types=1);

namespace Statik\Search\Common\Updater;

use Statik\Search\Common\Utils\NoticeManager;

/**
 * Class AbstractUpdater.
 */
abstract class AbstractUpdater
{
    protected array $registry = [];

    /**
     * AbstractUpdater constructor.
     */
    public function __construct(
        protected string $namespace,
        protected string $name,
        protected string $version,
        protected bool $isNetwork = false
    ) {
        if (\version_compare($this->version, $this->getDatabaseVersion(), '<=')) {
            return;
        }

        \add_action('admin_init', fn () => $this->executeUpdates());

        $this->isNetwork
            ? \add_action('network_admin_notices', fn () => $this->registerUpdateNotice())
            : \add_action('admin_notices', fn () => $this->registerUpdateNotice());
    }

    /**
     * Register update callbacks.
     */
    protected function registerUpdate(string $version, callable $callback): self
    {
        $this->registry[$version][] = $callback;

        return $this;
    }

    /**
     * Get the version from the database.
     */
    private function getDatabaseVersion(): string
    {
        static $databaseVersion = null;

        if (null === $databaseVersion) {
            $databaseVersion = $this->isNetwork
                ? (string) \get_network_option(null, "statik_version_{$this->namespace}")
                : (string) \get_option("statik_version_{$this->namespace}");

            if (empty($databaseVersion)) {
                $this->isNetwork
                    ? \update_network_option(null, "statik_version_{$this->namespace}", $this->version)
                    : \update_option("statik_version_{$this->namespace}", $this->version, false);
                $databaseVersion = $this->version;
            }
        }

        return $databaseVersion;
    }

    /**
     * Set the version in the database.
     */
    private function setDatabaseVersion(string $version): void
    {
        $this->isNetwork
            ? \update_network_option(null, "statik_version_{$this->namespace}", $version)
            : \update_option("statik_version_{$this->namespace}", $version, false);
    }

    /**
     * Register update notice.
     */
    private function registerUpdateNotice(): void
    {
        if ([] === $this->getUpdatesToExecute()) {
            return;
        }

        NoticeManager::warning(
            \__('The %s version of the plugin %s has been installed, but before it'
                . ' can be used properly it needs database migration. Please run %s script to install'
                . ' new requirements.', 'statik-commons'),
            [
                "<strong>{$this->version}</strong>",
                "<strong>{$this->name}</strong>",
                '<strong>' . \sprintf(
                    '<a href="%s">%s</a>',
                    \add_query_arg([
                        'statik_migration' => $this->namespace,
                        'nonce' => \wp_create_nonce('statik_migration'),
                    ]),
                    \__('the migration', 'statik-commons')
                ) . '</strong>',
            ],
            false
        );
    }

    /**
     * Get a list of updates that will be executed.
     */
    private function getUpdatesToExecute(): array
    {
        $toExecute = [];

        foreach ($this->registry as $version => $updates) {
            if (\version_compare($this->getDatabaseVersion(), $version, '>=')) {
                continue;
            }

            if (\version_compare($this->version, $version, '<')) {
                continue;
            }

            $toExecute = [...$toExecute, ...$updates];
        }

        return $toExecute;
    }

    /**
     * Execute update process.
     */
    private function executeUpdates(): void
    {
        if (\filter_input(\INPUT_GET, 'statik_migration') !== $this->namespace) {
            return;
        }

        if (false === \wp_verify_nonce(\filter_input(\INPUT_GET, 'nonce'), 'statik_migration')) {
            return;
        }

        $updates = $this->getUpdatesToExecute();

        if ([] === $updates) {
            return;
        }

        \array_map('call_user_func', $updates);

        $this->setDatabaseVersion($this->version);

        NoticeManager::flush();

        NoticeManager::success(
            \__('The migration to %s version of the plugin %s has been executed successfully.', 'statik-commons'),
            ["<strong>{$this->version}</strong>", "<strong>{$this->name}</strong>"]
        );

        \header('Location: ' . \remove_query_arg(['statik_migration', 'nonce']));
        exit;
    }
}
