<p align="center">
  <img width="400" src="assets/images/statik.png?raw=true" align="center" alt="Statik logo">
</p>

<h1 align="center">
  Statik Gutenberg Blocks Plugin
</h1>

This plugin has been prepared to add custom Blocks for use in the Gutenberg editor.

## ❓ What is in this document

- [💼 Main plugin features](#-main-plugin-features)
- [🧐 How does the plugin work?](#-how-does-the-plugin-work)
- [🚧 How to add custom strategy?](#-how-to-add-custom-block)
- [🔌 Filters and actions](#-filters-and-actions)
- [🪚 Development mode](#-development-mode)
- [🎉 Release procedure](#-release-procedure)

## 💼 Main plugin features

* dynamically register new Gutenberg blocks,
* allow creating Server Side Rendering blocks.

## 🧐 How does the plugin work?

The plugin register all custom Gutenberg blocks from the `blocks` directory. All blocks can be overridden from the theme
or other plugin level.

## 🚧 How to add custom block?

All blocks from the plugin can be overridden from the theme or other plugin level. Since version `0.10.0` blocks in the
plugin contain all source files that could be required to make changes in the block.

> :information_source: Note that, `BlocksManager` class during blocks registration base on the `slug` value from the
> block config, this value should be unique for new blocks and exactly the same when we want to override exists blocks.

To override block just copy the block source directory from the `blocks` directory and paste it somewhere in the theme.
The block class namespace should be changed because a duplicate class could be a PHP error reason. After that follow
snippet can be added to the `functions.php` file.

### Register single block

```php
\add_action('init', static function (): void {
    // Make sure that plugin is enabled.
    if (false === \class_exists('Statik\Blocks\BlockType\AbstractBlockType')) {
        return;
    }

    // Load all blocks classes.
    require_once \get_template_directory() . '/blocks/Icon/Icon.php';

    // Register block in the Blocks Manager.
    Statik\Blocks\DI::BlocksManager()->registerBlockType(
        BlockOverrides\Icon\Icon::class
    );
});
```

### Register blocks directory

Other solution is register the whole directory with blocks. The structure of the directory should be exactly the same
as `blocks` directory in the plugin root.

```php
\add_action('init', static function () {
    // Make sure that plugin is enabled.
    if (false === \class_exists('Statik\Blocks\BlockType\AbstractBlockType')) {
        return;
    }

    // Register directory in the Blocks Manager.
    Statik\Blocks\DI::BlocksManager()->registerBlocksDirectory(
        \get_template_directory() . '/project-blocks'
    );
});
```

> :warning: The `BlocksManager` class loads the LAST registered block with the same slug, so please make sure that the
> block loaded in the theme is loading in `init` action with priority between 10 and 20.

## 🔌 Filters and actions

The main processes running within the plugin are available for modification within WordPress filters and actions. When
using filters you should do it carefully, it is dedicated only to programmers who know and understand the effects of
using them.

All filters available within the plug-in have the same design. The filter name is always preceded by the name space
where the filter is located, like: `{plugin_namespace}\{filter_name}`.

## 🪚 Development mode

Development work on the plugin is only possible within a properly prepared package. Production version of the plugin
cannot be launched in full development mode due to lack of many required files.

The development mode in the plugin can be activated by the constant `STATIK_BLOCKS_DEVELOPMENT`. When the value of the
constant is set to true, it is loaded on the dashboard and not the minified asset version.

Download development dependencies The development plugin requires the installation of external dependencies, such as
Composer or NPM dependencies.

To start working, run the following commands:

```bash
composer install
npm install
```

### Scripts

Actions, like compiling the SASS files or build JS files, are managed by `npm` scripts. All available tasks can be found
in `package.json`.

## 🎉 Release procedure

The process of publishing a new version of the plugins should follow the steps below. During this process a significant
part of the work is done by Github Actions. After releasing a new version of the plugin catalog is zipped and sent to
Statik bucket on AWS infrastructure. Thanks to this plugin can be downloaded directly to any of our WordPress instances.

> :warning: Make sure that all changes are developing on feature branches. During the release procedure, all changes
> from the selected branch will be included in the release.

### Procedure for releasing a new version of the plugin

1. All new changes that should be included in the new release should be pushed to the selected branch.
2. Run the Github Action script named `Create package release`. Action has to be run manually on a selected branch with
   an extra parameter contain the new version of the plugin.
3. When the script finish in the Release tab should be visible a new one.
4. That's all, the new version of the plugin has been released 🎉

## 📝 Licence

The Statik Deployment Plugin is licensed under the GPLv3 or later.
