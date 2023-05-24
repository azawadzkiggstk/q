<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\Deploy\Dashboard\AdminBar;

/**
 * @var AdminBar $this
 * @var string   $envTime
 */
?>
<span class="ab-icon small last"> </span>
<span class="btn-wrap"><?= $envTime; ?></span>
