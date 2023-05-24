# Change Log

## [v4.0.3](https://github.com/statik-space/wordpress-statik-deployment/tree/v4.0.3) - 10.03.2023

### Added

### Changed

### Fixed

- fixed fatal error when deployment meta is empty.

## [v4.0.2](https://github.com/statik-space/wordpress-statik-deployment/tree/v4.0.2) - 09.03.2023

### Added

### Changed

### Fixed

- fixed warning in preview mode.

## [v4.0.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v4.0.1) - 09.03.2023

### Added

### Changed

### Fixed

- fixed warning in preview mode.

## [v4.0.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v4.0.0) - 21.02.2023

### Added

- added an option to trigger deployment without set it as release,
- added an option to flush cache during deployment.

### Changed

- changed support from v4 to v5 in Statik provider,
- updated external dependencies.

### Fixed

## [v3.13.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.13.1) - 03.01.2023

### Added

### Changed

### Fixed

- fixed deployment URL if website is a multisite blog,
- removed admin bar from preview loader,
- fixed deployments history table buttons.

## [v3.13.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.13.0) - 02.01.2023

### Added

- support for preview functionality.

### Changed

- updated external dependencies,
- provider code refactor.

### Fixed

## [v3.12.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.12.1) - 09.11.2022

### Added

### Changed

### Fixed

- fixed release procedure.

## [v3.12.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.12.0) - 09.11.2022

### Added

### Changed

- updated external dependencies.

### Fixed

## [v3.11.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.11.0) - 25.10.2022

### Added

### Changed

- updated external dependencies.

### Fixed

## [v3.10.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.10.0) - 26.09.2022

### Added

### Changed

- reorganised settings UI,
- small copy improvements.

### Fixed

- updated composer dependencies.

## [v3.9.3](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.9.3) - 14.09.2022

### Added

### Changed

### Fixed

- issue with `query_strict` in Statik API deployment history,
- fixed issue with display to many results in the deployment history.

## [v3.9.2](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.9.2) - 02.09.2022

### Added

### Changed

### Fixed

- fixed tooltips,
- fixed querying history in multisite instances.

## [v3.9.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.9.1) - 01.09.2022

### Added

### Changed

### Fixed

- fixed async selects which returns 404.

## [v3.9.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.9.0) - 29.08.2022

### Added

### Changed

- updated Composer vendors to the latest versions.

### Fixed

## [v3.8.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.8.1) - 24.08.2022

### Added

### Changed

### Fixed

- fixed issue with not opening frontend environment for Custom Post Types,
- fixed issue with deployment log feel.

## [v3.8.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.8.0) - 05.08.2022

### Added

- added cache to deployment API requests, to avoid too many requests,
- added an option to disable the dashboard locker (not recommended in the production environment).

### Changed

- updated Composer vendors to the latest versions.

### Fixed

- fixed issue with incorrect site prefix in the `Open environment` button URL.

## [v3.7.3](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.7.3) - 15.07.2022

### Added

### Changed

### Fixed

- small tweaks and improvements.

## [v3.7.2](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.7.2) - 24.06.2022

### Added

### Changed

- updated Composer vendors to the latest versions.

### Fixed

## [v3.7.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.7.1) - 08.06.2022

### Added

### Changed

### Fixed

- fixed incorrect trailing slash in the View frontend functionality,
- fixed potential issues when Config `getKeys` method returns nothing.

## [v3.7.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.7.0) - 03.06.2022

### Added

- added an option to give Deployments a name - work only for Statik provider,
- added possibility to manage which historical release is currently displayed - works only for Statik provider,
- added the `View environment` option in settings.

### Changed

- refactored Deployment History table, to work with Provider API data,
- updated Statik Commons to version 3.3.0.

### Fixed

## [v3.6.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.6.0) - 28.04.2022

### Added

- added protection for empty `Site path prefix` in Statik provider.

### Changed

### Fixed

- fixed issue with incorrect `Site path prefix` when deploy to Statik in multisite instance.

## [v3.5.5](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.5.5) - 26.04.2022

### Added

### Changed

### Fixed

- fixed issue global WP styles overriding.

## [v3.5.4](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.5.4) - 22.04.2022

### Added

### Changed

### Fixed

- fixed issue with fatal error in the Deployment history tab,
- fixed issue with missing Deployment logs.

## [v3.5.3](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.5.3) - 06.04.2022

### Added

### Changed

### Fixed

- fixed issue with fatal error in the Deployment history tab,
- fixed issue with missing Deployment logs.

## [v3.5.2](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.5.2) - 06.04.2022

### Added

### Changed

### Fixed

- fixed issue with fatal error in the Deployment history tab.

## [v3.5.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.5.1) - 30.03.2022

### Added

### Changed

### Fixed

- fixed issue when deployment is not continuing in the dashboard.

## [v3.5.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.5.0) - 30.03.2022

### Added

### Changed

- updated Statik Common package to version 3.2.0,
- changed REST API namespace from `statik` to `statik-deployment`.

### Fixed

## [v3.4.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.4.0) - 24.03.2022

### Added

- added support for plugin translations.

### Changed

### Fixed

## [v3.3.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.3.0) - 03.03.2022

### Added

### Changed

- changed Statik communication API to version 3.

### Fixed

## [v3.2.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.2.0) - 18.02.2022

### Added

### Changed

- changed `PostLogger` date display format,
- changed `DeploymentLogger` date display format.

### Fixed

- fixed `PostLogger` functionality to avoid duplicates.

## [v3.1.3](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.1.3) - 14.02.2022

### Added

### Changed

### Fixed

- fixed deployment lock popup.

## [v3.1.2](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.1.2) - 08.02.2022

### Added

### Changed

### Fixed

- fixed deployment lock popup feeling,
- fixed broken log modal, when content contains HTML tags.

## [v3.1.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.1.1) - 01.02.2022

### Added

### Changed

### Fixed

- fixed fatal error in Deployment when no environment exists.

## [v3.1.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.1.0) - 01.02.2022

### Added

### Changed

- changed History table behavior to improve UX.

### Fixed

## [v3.0.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.0.1) - 05.01.2022

### Added

### Changed

### Fixed

- fixed issue with incorrect partials path.

## [v3.0.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v3.0.0) - 22.12.2021

### Added

- added dashboard lock modal when deployment is in progress, to prevent situations when user update something during the deployment.

### Changed

- changed the minimum PHP version from 7.4 to 8.0.
- improved javascript functionalities, now everything is stored on `statikDeploy` object,
- changed the way of storing current deployment to be more universal.

### Fixed

- small tweak fixes and improvements.

## [v2.7.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.7.0) - 24.11.2021

### Added

### Changed

- improved settings labels to be more intuitive,
- updated Statik Commons to version 2.2,
- improved the deployment logger design.

### Fixed

## [v2.6.2](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.6.2) - 16.11.2021

### Added

### Changed

### Fixed

- fixed dashboard fatal error when no environment is selected.

## [v2.6.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.6.1) - 10.11.2021

### Added

### Changed

### Fixed

- restore permissions check in the Deployment REST API endpoint.

## [v2.6.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.6.0) - 10.11.2021

### Added

- added support for display deployments logs in the dashboard,
- added option to open frontend website from the dashboard deployment page.

### Changed

- refactor deployments history table,
- improved deployment dashboard page.

### Fixed

- fixed issues with handling errors in the deployment process.

## [v2.5.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.5.1) - 14.10.2021

### Added

### Changed

### Fixed

- updated Statik Commons to version 2.1.6 to prevent warnings.

## [v2.5.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.5.0) - 13.10.2021

### Added

- added timeout in API requests.

### Changed

- improved labels to be more readable.

### Fixed

- fixed issue with stuck deployments in the history.

## [v2.4.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.4.1) - 29.09.2021

### Added

### Changed

### Fixed

- fixed issue with display deployed posts in the History.

## [v2.4.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.4.0) - 29.09.2021

### Added

### Changed

- improved Deployment Page behavior, reduced the number of steps,
- changed counter to count time in a more human-readable format.

### Fixed

- fixed issue with display deployed posts in the History.

## [v2.3.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.3.1) - 20.09.2021

### Added

### Changed

### Fixed

- restored missing `permission_callback` function in API endpoint.

## [v2.3.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.3.0) - 08.09.2021

### Added

- added `Debug` mode, when raw response from API is present in WP REST API.

### Changed

- moved all settings into one tab.

### Fixed

## [v2.2.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.2.0) - 03.09.2021

### Added

### Changed

- updated Statik Common to 2.1.2 version,
- changed messages that are displayed in the deployment to be consistent with the latest Statik API version,
- changed `StatikProvider` API responses data to be consistent with the latest Statik API version.
- small stylesheets improvements.

### Fixed

## [v2.1.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.1.0) - 19.08.2021

### Added

### Changed

- updated Statik Common to 2.1.0 version.

### Fixed

- fixed issues with `Updater` class.

## [v2.0.4](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.0.4) - 19.08.2021

### Added

### Changed

- updated Statik Common to 2.0.6 version.

### Fixed

- fixed warnings in DI.

## [v2.0.3](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.0.3) - 28.07.2021

### Added

### Changed

- updated Statik Common to 2.0.3 version.

### Fixed

- remove sourcemaps from production assets.

## [v2.0.2](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.0.2) - 22.07.2021

### Added

### Changed

- updated Statik Common to 2.0.2 version.

### Fixed

- fixed issue with `Updater` class.

## [v2.0.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.0.1) - 20.07.2021

### Added

### Changed

- updated Statik Common to 2.0.1 version.

### Fixed

- fixed missing settings page.

## [v2.0.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v2.0.0) - 16.07.2021

### Added

### Changed

- since version 2.0.0 there is no requirement to keep Statik Commons in the same version for all plugins,
- made required changes for implement Statik Commons v2.0.0.

### Fixed

- fixed typos and updated descriptions in settings.

## [v1.2.2](https://github.com/statik-space/wordpress-statik-deployment/tree/v1.2.2) - 18.06.2021

#### Required Statik Commons version - [v1.2.\*](https://github.com/statik-space/wordpress-statik-commons/tree/v1.2.0)

### Added

### Changed

### Fixed

- fixed issue with missing `megamenu` CPT in default settings.

## [v1.2.1](https://github.com/statik-space/wordpress-statik-deployment/tree/v1.2.1) - 17.06.2021

#### Required Statik Commons version - [v1.2.\*](https://github.com/statik-space/wordpress-statik-commons/tree/v1.2.0)

### Added

### Changed

- improved stylesheets on the deployment page.

### Fixed

- fixed `Config` class and behavior when the default settings contain incorrect JSON,
- fixed default values in the settings pages.

## [v1.2.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v1.2.0) - 16.06.2021

#### Required Statik Commons version - [v1.2.\*](https://github.com/statik-space/wordpress-statik-commons/tree/v1.2.0)

### Added

- added strict typing and data casting in paces where are get from the Config.

### Changed

- styles improvements and cleanup,
- updated Statik Commons vendor to 1.2.0 version,
- refactored `Helper` namespace and change it to `Utils` for more consistency,
- updated Composer vendors to the latest versions.

### Fixed

- fixed incorrect data passing to the API in Statik Provider.

## [v1.1.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v1.1.0) - 17.05.2021

#### Required Statik Commons version - [v1.1.\*](https://github.com/statik-space/wordpress-statik-commons/tree/v1.1.0)

### Added

- introduced the Changelog file.

### Changed

- updated Statik Commons vendor to 1.1.0 version,
- changed Checkboxes values behavior based on Commons update,
- updated Composer vendors to the latest versions.

### Fixed

## [v1.0.0](https://github.com/statik-space/wordpress-statik-deployment/tree/v1.0.0) - 02.04.2021

#### Required Statik Commons version - [v1.0.\*](https://github.com/statik-space/wordpress-statik-commons/tree/v1.0.0)

### Added

- introduced the Changelog file,
- introduced style improvements.

### Changed

- updated Statik Commons vendor to 1.0.0 version,
- updated Composer vendors to the latest versions,
- code cleanup, removed legacy code.

### Fixed
