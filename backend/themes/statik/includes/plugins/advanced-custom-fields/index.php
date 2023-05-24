<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

if (false === \class_exists('ACF')) {
    return;
}

\add_filter('acf/settings/save_json', 'statik_save_acf_json');

\add_filter('acf/settings/load_json', 'statik_load_acf_json');

if (false === \function_exists('statik_save_acf_json')) {
    /**
     * Define a directory where ACF JSON should be saved.
     */
    function statik_save_acf_json(): string
    {
        return __DIR__ . '/dynamic';
    }
}

if (false === \function_exists('statik_load_acf_json')) {
    /**
     * Define a directory from where ACF JSON should be loaded.
     */
    function statik_load_acf_json(): array
    {
        return [__DIR__ . '/dynamic'];
    }
}
