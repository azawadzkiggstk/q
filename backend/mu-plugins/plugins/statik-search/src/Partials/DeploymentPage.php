<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Illuminate\Support\Arr;
use Statik\Deploy\DI as DeployDI;
use Statik\Search\Dashboard\Page\DeploymentPage;
use Statik\Search\DI;

/**
 * @var DeploymentPage $this
 * @var string         $environmentName
 */
$searchInProgress = \get_transient("statik_search.{$environmentName}.in_progress_search");
$environmentsConfig = (array) DeployDI::Config()->get('env', []);

$emptySettings = empty(DI::Config()->getValue('search.endpoint_url'))
    || empty(DI::Config()->getValue('search.client_key'));

$tooltip = \__(
    'Search cannot be updated because of missing configuration. Go to settings and fill API hook, and API token.',
    'statik-search'
);

$searchButtonLabel = \sprintf(
    \__('Update %s search engine', 'statik-search'),
    Arr::get($environmentsConfig[$environmentName], 'values.name.value') ?: \__('[Untitled environment]', 'statik-search')
);

?>
<button class="button button-large button-primary js-action-button <?= $searchInProgress ? 'loading' : ''; ?>"
        onclick="return window.statikSearch.instance.searchUpdateConfirm(event)" <?= \disabled($emptySettings); ?>
    <?= $emptySettings ? "data-tootik=\"{$tooltip}\" data-tootik-conf=\"warning multiline square\"" : null; ?>>
    <i class="dashicons-before dashicons-search"> </i>
    <?= $searchButtonLabel; ?>
</button>