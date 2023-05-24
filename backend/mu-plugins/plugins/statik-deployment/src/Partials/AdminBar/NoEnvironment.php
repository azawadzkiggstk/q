<?php

declare(strict_types=1);

\defined('WPINC') || exit;

use Statik\Deploy\Dashboard\AdminBar;

/**
 * @var AdminBar $this
 * @var string   $label
 */
?>
<span class="ab-icon small no-env"> </span>
<span class="status btn-wrap"><?= $label; ?></span>
