<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\Deploy\Dashboard\AdminBar;

/**
 * @var AdminBar $this
 * @var string   $envUser
 */
?>
<span class="ab-icon small user"> </span> <?= $envUser; ?>
