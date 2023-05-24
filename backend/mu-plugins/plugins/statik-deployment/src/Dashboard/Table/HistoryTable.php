<?php

declare(strict_types=1);

namespace Statik\Deploy\Dashboard\Table;

use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\Deployment\Error\DeploymentException;
use Statik\Deploy\Deployment\Provider\Deployment\AbstractDeployment;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\DI;
use Statik\Deploy\Utils\Tooltip;
use Statik\Deploy\Utils\User;

/**
 * Class HistoryTable.
 */
class HistoryTable extends \WP_List_Table
{
    public const ITEMS_PER_PAGE = 20;

    private ?string $error = null;

    private string $timeFormat;
    private bool $isPreview;
    private DeploymentManager $deploymentManager;
    private string $frontUrl;

    /**
     * HistoryTable constructor.
     */
    public function __construct(private string $environment)
    {
        parent::__construct([
            'singular' => \__('Deployment history', 'statik-deployment'),
            'plural' => \__('Deployment history', 'statik-deployment'),
        ]);

        $this->timeFormat = \get_option('date_format') . ' ' . \get_option('time_format');
        $this->isPreview = (bool) ($_REQUEST['preview'] ?? false);
        $this->deploymentManager = new DeploymentManager($this->environment);
        $this->frontUrl = \untrailingslashit($this->deploymentManager->getProvider()?->getFrontUrl());
    }

    /**
     * {@inheritdoc}
     */
    public function prepare_items(): void
    {
        $this->_column_headers = [$this->get_columns(), [], $this->get_sortable_columns()];

        try {
            $history = $this->deploymentManager
                ->fetch(static::ITEMS_PER_PAGE, $this->get_pagenum(), $this->isPreview);
        } catch (DeploymentException $e) {
            $this->error = $e->getMessage();
            $history = [];
        }

        $this->items = $history['data'] ?? [];

        $this->set_pagination_args([
            'total_items' => $history['total'] ?? 0,
            'per_page' => $history['per_page'] ?? self::ITEMS_PER_PAGE,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function no_items(): void
    {
        include_once DI::dir('src/Partials/Tables/HistoryNoResults.php');
    }

    /**
     * Handle status column.
     */
    public function column_status(AbstractDeployment $deploy): string
    {
        if ($this->frontUrl && $deploy->hasStatus(DeploymentInterface::READY)) {
            $actions['view'] = \sprintf(
                '<a href="%s" target="_blank">%s</a>',
                $deploy->getUrl(),
                match (true) {
                    $deploy->isLive() => \__('View website', 'statik-deployment'),
                    $deploy->isPreview() => \__('Preview', 'statik-deployment'),
                    default => \__('View release', 'statik-deployment')
                }
            );
        }

        if (
            false === $deploy->isLive()
            && $deploy->hasStatus(DeploymentInterface::READY)
            && false === $deploy->isPreview()
        ) {
            $actions['live'] = \sprintf(
                '<form method="POST" style="display:inline;" onsubmit="%s">
                    %s
                    <input type="hidden" name="statik_deployment_release[id]" value="%s" />
                    <input type="hidden" name="statik_deployment_release[environment]" value="%s" />
                    <button type="submit" class="button-link" style="color:#b32d2e;">%s</button>
                </form>',
                'return window.statikDeployment.instance.rollbackReleaseConfirm(event)',
                \wp_nonce_field('statik_deployment_release_nonce', '_deploy_release_nonce'),
                $deploy->getId(),
                $this->environment,
                \__('Rollback to this deployment', 'statik-deployment'),
            );
        }

        $label = \sprintf(
            '<span class="status status-%s"></span> %s',
            \sanitize_title($deploy->getStatus()),
            \ucfirst(\strtolower(\str_replace('_', ' ', $deploy->getStatus())))
        );

        if ($deploy->isLive()) {
            $label .= ' <span class="live">(Live)</span>';
        }

        if ($deploy->getName()) {
            $label .= ": <i>{$deploy->getName()}</i>";
        }

        return \sprintf('%1$s %2$s', $label, $this->row_actions($actions ?? []));
    }

    /**
     * Handle deployed posts column.
     */
    public function column_deployed_posts(AbstractDeployment $deploy): string
    {
        if ($deploy->hasStatus(DeploymentInterface::ERROR) && false === $deploy->isPreview()) {
            return '';
        }

        $posts = \array_map(
            static fn ($id): string|int => \get_post_status($id)
                ? \sprintf('<a href="%1$s">%2$s</a>', \get_edit_post_link($id), \get_the_title($id))
                : Tooltip::add(\__('The post cannot be recognized because it may have been deleted.'), "[ID: {$id}]"),
            $deploy->getMeta('posts') ?: []
        );

        $show = \array_slice($posts, 0, 5);
        $hide = \array_slice($posts, 5);

        $more = \count($hide) ? \sprintf(
            '<span class="js-show-more"><button class="button-link">%s</button><span style="display:none;">%s</span></span>',
            \sprintf(\__('...and %d more', 'statik-deploy'), \count($hide)),
            \implode(', ', $hide)
        ) : null;

        return \html_entity_decode(\implode(', ', \array_filter([...$show, $more])));
    }

    /**
     * Handle user column.
     */
    public function column_user(AbstractDeployment $deploy): string
    {
        $user = \get_user_by('email', $deploy->getMeta('user.email')) ?: $deploy->getMeta('user.email');

        if ($user instanceof \WP_User) {
            $actions['profile'] = \sprintf(
                '<a href="%s" target="_blank">View profile</a>',
                \get_edit_user_link($user->ID)
            );
        }

        if ($deploy->useCredentials()) {
            $lock = Tooltip::add(
                \__(
                    'The deployment used one-time user credentials to confirm access to resources.',
                    'statik-deployment'
                ),
                '<span class="dashicons dashicons-unlock"></span>'
            );
        }

        return \sprintf('%s%s %s', User::resolve($user, true), $lock ?? '', $this->row_actions($actions ?? []));
    }

    /**
     * Handle start column.
     */
    public function column_start(AbstractDeployment $deploy): string
    {
        $actions = [
            'time' => \wp_date($this->timeFormat, $deploy->getStartTime()),
        ];

        return \sprintf(
            /* translators: 1 - Human readable time since last deployment, 2 - Deployment date */
            \__('~%1$s ago %2$s', 'statik-deployment'),
            \human_time_diff($deploy->getStartTime()),
            $this->row_actions($actions)
        );
    }

    /**
     * Handle duration column.
     */
    public function column_duration(AbstractDeployment $deploy): string
    {
        if ($deploy->hasStatus(DeploymentInterface::IN_PROGRESS)) {
            return '';
        }

        $actions = [
            'time' => \wp_date($this->timeFormat, \max($deploy->getEndTime(), 0)),
        ];

        return \sprintf(
            /* translators: 1 - Human readable duration of last deployment, 2 - Deployment date */
            \__('~%1$s %2$s', 'statik-deployment'),
            \human_time_diff($deploy->getStartTime(), \max($deploy->getStartTime(), $deploy->getEndTime())),
            $this->row_actions($actions)
        );
    }

    /**
     * Handle log column.
     */
    public function column_log(AbstractDeployment $deploy): string
    {
        return \sprintf(
            '<a href="%s" onclick="%s" class="thickbox log-button">%s</a>',
            '#TB_inline?&width=800&height=500&inlineId=statik-deploy-log-modal',
            "window.statikDeployment.instance.showLogModal('{$deploy->getId()}')",
            \__('View build log', 'statik-deployment')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get_columns(): array
    {
        return [
            'status' => \__('Status', 'statik-deployment'),
            'deployed_posts' => \sprintf(
                '%s %s',
                $this->isPreview
                    ? \__('Preview post', 'statik-deployment')
                    : \__('Deployed changes', 'statik-deployment'),
                Tooltip::add(\__(
                    'This list may be incomplete or incorrect, in case the previous deployment has not been done '
                    . 'in this WordPress instance.',
                    'statik-deployment'
                ))
            ),
            'user' => \__('Performed by', 'statik-deployment'),
            'start' => \__('Start', 'statik-deployment'),
            'duration' => \__('Duration', 'statik-deployment'),
            'log' => \__('Log', 'statik-deployment'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function single_row($item): void
    {
        /** @var AbstractDeployment $item */
        echo \sprintf(
            '<tr class="row-%s%s">',
            \strtolower($item->getStatus()),
            $item->isLive() ? ' row-live' : ''
        );
        $this->single_row_columns($item);
        echo '</tr>';
    }

    /**
     * {@inheritDoc}
     */
    protected function extra_tablenav($which): void
    {
        if (false === DI::isPreview() || 'bottom' === $which) {
            parent::extra_tablenav($which);

            return;
        }

        ?>
        <form method="get" style="display:inline;">
            <input type="hidden" name="page" value="statik_deployment_history"/>
            <input type="hidden" name="env" value="<?= $this->environment; ?>"/>
            <label for="filter-by-preview" class="screen-reader-text">
                <?= \__('Filter by type', 'statik-deployment'); ?>
            </label>
            <select name="preview" id="filter-by-preview">
                <option<?php \selected($this->isPreview, false); ?> value="">
                    <?= \__('Regular deployments', 'statik-deployment'); ?>
                </option>
                <option<?php \selected($this->isPreview); ?> value="1">
                    <?= \__('Preview deployments', 'statik-deployment'); ?>
                </option>
            </select>
            <?php \submit_button(\__('Filter', 'statik-deployment'), 'filter_action', wrap: false); ?>
        </form>
        <?php

        parent::extra_tablenav($which);
    }
}
