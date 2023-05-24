<p align="center">
  <img width="400" src="assets/images/statik.png?raw=true" align="center" alt="Statik logo">
</p>

<h1 align="center">
  Statik Deployment Plugin
</h1>

The plugin has been prepared to manage the implementation process of changes within the popular JAMstack infrastructure.
Using services such as Vercel or Netlify it is possible to carry out the process of implementing changes to the
JavaScript application. After providing appropriate settings, the plugin allows implementing changes directly from the
dashboard level.

## ❓ What is in this document

- [💼 Main plugin features](#-main-plugin-features)
- [🧐 How does the plugin work?](#-how-does-the-plugin-work)
- [🧰 Plugin configuration](#-plugin-configuration)
- [🚧 How to add a custom deployment provider?](#-how-to-add-a-custom-deployment-provider)
- [🔌 Filters and actions](#-filters-and-actions)
- [🪚 Development mode](#-development-mode)
- [🎉 Release procedure](#-release-procedure)

## 💼 Main plugin features

* implementing changes to the JAMstack infrastructure from the WordPress dashboard,
* monitoring current implementations, and storing history,
* monitor and report on current modifications before implementation.

## 🧐 How does the plugin work?

The plugin monitors all changes created in different types of posts and informs about the possibility of their
implementation. When implemented, a request is sent to the service provider to start the application building process.
Javascript application is already responsible for the rest of the application deployment process.

## 🧰 Plugin configuration

By default Statik Deployment plugin can use 3 different providers to use as a headless CMS: `Statik`, `Vercel`, and
`Netlify`. There can be added more providers what is described in
[How to add a custom deployment provider?](#-how-to-add-a-custom-deployment-provider) section.

The configuration of each provider is a little bit different. All required values can be found in the provider's
dashboard.

### Deployment automation

In the `Deployment automation settings` tab can be defined when automated deployments should execute. The most common
case is when a scheduled post is publishing. Also, there can be added fully custom executions based on the time.

### History control

Statik Deployment plugin can control changes in the Custom Post Types and inform before the deployment about all made
changes. In the `Deployment history settings` can be defined which CPTs are supported by the History control. To make
sure that history works correctly, each CPT should have Revisions enable -
[How to enable Revisions in CPT?](https://developer.wordpress.org/reference/functions/register_post_type/#supports)

## 🚧 How to add a custom deployment provider?

The plugin allows adding a custom deployment Provider from Theme level. Added Provider requires to be the implementation
of the `Statik\Deploy\Deployment\Provider\ProviderInterface` interface.

The following methods are required for the implementation of the interface:

* `triggerDeployment()` - the provider should call here the appropriate endpoint API which will start the process of
  building a JavaScript application.
* `getDeploymentHistory()` - The provider should call the appropriate endpoint API here, which will return detailed
  information about the recently launched building process together with information about its execution status.

In the configuration, the value `env.{envId}.in_progress` is available with the time stamp of the last deployment. When
the value is not null, then it is not possible to start another implementation, and the previous one is continued
automatically by User interface.

```php
\add_filter( 
    'Statik\Deploy\customProviders', 
    fn (array $customProviders): array => \array_merge(
        $customProviders, 
        ['Custom Provider' => CustomProvider::class]
    )
);
```

## 🔌 Filters and actions

The main processes running within the plugin are available for modification within WordPress filters and actions. When
using filters you should do it carefully, it is dedicated only to programmers who know and understand the effects of
using them.

All filters available within the plug-in have the same design. The filter name is always preceded by the name space
where the filter is located, like: `{plugin_namespace}\{filter_name}`.

## 🪚 Development mode

Development work on the plugin is only possible within a properly prepared package. Production version of the plugin
cannot be launched in full development mode due to lack of many required files.

The development mode in the plugin can be activated by the constant `STATIK_DEPLOY_DEVELOPMENT`. When the value of the
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

The Statik Deployment Plugin is licensed under the GPLv3 or later.
