<p align="center">
  <img width="400" src="assets/images/statik.png?raw=true" align="center" alt="Statik logo">
</p>

<h1 align="center">
  Statik Mega Menu Plugin
</h1>

This plugin was prepared to access WP Menus by REST API endpoints.

## ❓ What is in this document

- [💼 Main plugin features](#-main-plugin-features)
- [🔌 Filters and actions](#-filters-and-actions)
- [🪚 Development mode](#-development-mode)
- [🎉 Release procedure](#-release-procedure)

## 💼 Main plugin features

* access WP Menus by REST API Endpoints,
* support for ACF and Static Gutenberg REST API.

## 🔌 Filters and actions

The main processes running within the plugin are available for modification within WordPress filters and actions. When
using filters you should do it carefully, it is dedicated only to programmers who know and understand the effects of
using them.

All filters available within the plug-in have the same design. The filter name is always preceded by the name space
where the filter is located, like: `{plugin_namespace}\{filter_name}`.

## 🪚 Development mode

Development work on the plugin is only possible within a properly prepared package. Production version of the plugin
cannot be launched in full development mode due to lack of many required files.

The development mode in the plugin can be activated by the constant `STATIK_MENU_DEVELOPMENT`. When the value of the
constant is set to true, it is loaded on the dashboard and not the minified asset version.

Download development dependencies The development plugin requires the installation of external dependencies, such as
Composer or NPM dependencies.

To start working, run the following commands:

```bash
composer install
npm install
```

### Gulp tasks

Actions, like compiling the SASS files or build JS files, are managed by Gulp tasks. All available tasks can be found
in `gulpfile.js`.

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

The Statik Mega Menu Plugin is licensed under the GPL v3 or later.
