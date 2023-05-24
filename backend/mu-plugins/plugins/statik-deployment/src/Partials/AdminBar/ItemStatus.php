<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\Deploy\Dashboard\AdminBar;

/**
 * @var AdminBar $this
 * @var string   $envToDeploy
 */
?>
<span class="ab-icon small deploy"> </span>
<span class="status btn-wrap"><?= $envToDeploy; ?></span>
