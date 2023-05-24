<p align="center">
  <img width="400" src=".github/images/statik.jpg?raw=true" align="center" alt="Statik logo">
</p>

<h1 align="center">
  Project Boilerplate
</h1>

This repository is a Gatsby.js starter for Statik based projects. You can actually consider it as a scaffolding for all 
Statik websites with most common features available out of the box. From a technical point of view, it utilises 
WordPress as a data provider and Gatsby as a static page generator, so in fact each repository ships both 
front-end and back-end.

## ❓ What is in this document

- [📕 Prerequisites](#-prerequisites)
- [🖥 Getting Started with a Gatsby.js frontend app](#-getting-started-with-gatsbyjs-frontend-app)
- [🎛 Getting Started with a WordPress backend](#-getting-started-with-a-wordpress-backend)

## 📕 Prerequisites

Access to the repository itself and dependencies consumed by the project (such as npm packages and docker images) is 
restricted to authenticated users with proper permissions to `@statik-space` Github space. See sections below to learn 
how to log-in programmatically and gain access to all resources.

### Personal access token

It is a Github token to log-in without a need to put your password compromised in plain-text file. It is highly 
recommended to create a separate token for different kind of actions. In most cases you will need repo details and 
ability to read packages list. In order to create credentials, please follow instructions below:

1. Navigate to Github settings (simply click avatar in top-right part of the screen and hit Settings),
2. Select "Developer Settings" and then "Personal access tokens" out of a side menu,
3. Add new Personal access token, name it `yarn install` or similarly and assign it the following scopes:

- `repo:status`
- `repo:repo_deployment`
- `repo:public_repo`
- `repo:invite`
- `read:packages`

4. Generate the token and save it for the next step.

### Authentication

This step is basically about telling yarn to use github registry instead of default npmjs for all packages prefixed 
with `@statik-space`. In case authentication is incomplete or invalid you will likely get 401 or 403 error when 
installing npm packages.

To set you Personal Access Token simply run

```
export STATIK_REGISTRY_TOKEN=___YOUR_TOKEN___
```

remember to update `___YOUR_TOKEN___` with your Personal Access Token.

**Please note**, `.npmrc` is no longer necessary.

## 🖥 Getting Started with Gatsby.js frontend app

### Yarn 2

The project utilizes features available only in yarn 2.x.x. Just make sure you use the latest version of yarn (you can 
upgrade using brew or npm).

Then navigate to project's root directory and run the following command to validate whether you run yarn 2.x.x:

```
~ yarn -v
2.4.2
```

### Frontend `.env` file

`.env` is a configuration file which is responsible for defining environment specific variables such as data provider 
URL, some unique tokens for WordPress authentication or output URL. To learn more about `.env` itself or it syntax, 
please navigate to the [project website](https://www.npmjs.com/package/dotenv#rules). The file is meant to be placed 
within `/frontend` directory and to be unique for each installation of Gatsby.js. Please note it should be **NEVER** 
committed to the repository, as each environment configuration might be slightly different.

**@todo:** `.env` generation file will be handled by Statik CLI at some point.

**Available variables**

Most of `.env` variables are consumed by theme dependency (`gatsby-theme-statik`), if you look for more details of how 
specific variables are consumed remember to scan the mentioned package as well. Below you can find a list of most common 
variables consumed either by project repository or its npm packages.

**`GATSBY_BASE_PATH`**
Defines a directory in which Gatsby.js website should be operating. This is very useful for multisite installations, 
so each site from a network could be built individually. Please note this variable has no impact on the development 
environment.

```
# Single site
GATSBY_BASE_PATH=/

# A site from the network
GATSBY_BASE_PATH=/individual-investors/
```

**`GATSBY_BASE_URL`**
Defines an output URL of the Gatsby.js application. Be sure to use a full URL with a protocol and a trailing slash.

```
# Example
GATSBY_BASE_URL=https://ancient-robot-11.statik.space/
```

**`WORDPRESS_API_URL`**
Defines a URL of the WordPress instance. Be sure to use a full URL with a protocol and a trailing slash.

```
# Example
GATSBY_BASE_URL=https://ancient-robot-11-dashboard.statik.space/
```

**`GRAVITY_FORMS_CONSUMER_KEY`** and **`GRAVITY_FORMS_CONSUMER_SECRET`**
Gravity Form keys to fetch form instances from the WP API by the React.js application. In case no keys are provided, 
forms will be not rendered properly.

```
# Example
GRAVITY_FORMS_CONSUMER_KEY="ck_ddddaaaaaaaaa00003333aaaaffff0000bbc0000"
GRAVITY_FORMS_CONSUMER_SECRET="cs_ddddaaaaaaaaa00003333aaaaffff0000bbc0000"
```

**`SATELLITE_FORMS_API_ENDPOINT`** and **`SATELLITE_FORMS_API_TOKEN`**
Statik Search Satellite settings to empower a search engine. Please note in case no variables are provided, 
search will not work properly.

```
# Example
SATELLITE_FORMS_API_ENDPOINT=https://forms.statik-stg.app
SATELLITE_FORMS_API_TOKEN="0000000000000000000000000000000000000000000000000000000000000000"
```

**`SENTRY_DSN`**
Sentry integration, which captures all front-end errors in a dashboard which can be accessed by developers. This 
setting will not affect development environments

```
# Example
SENTRY_DSN=https://00000000000000000000000000000000@0000000.ingest.sentry.io/0000000
```

**Type** Object of key value pairs.

Even if `env` is not passed directly in the configuration, it is read in this dependency directly from the `.env` file. 
Any variables passed in this object might affect how the website behaves, see below for some patterns explained.

- Existing `GRAVITY_FORMS_CONSUMER_KEY` and `GRAVITY_FORMS_CONSUMER_SECRET` keys will enable Gravity Forms integration,
- Existing `GOOGLE_MAPS_API_KEY` key will enable Google Maps integration
- Existing `FORMS_API_ENDPOINT` and `FORMS_API_TOKEN` keys will enable Gravity Forms front-end integration,
- Existing `SEARCH_API_ENDPOINT` and `SEARCH_API_TOKEN` keys will enable Statik search integration,
- Existing `SENTRY_DSN` will enable Sentry integration.

**Please note** If you look for an explanation how `.env` file works like, or how to create it, please head to project 
(or boilerplate) documentation instead.

### Frontend dependencies

Just before you head to the installation step, navigate to project's root directory, and make sure your cache is clear 
with the following command

```
yarn cache clean
```

Assuming you can now connect with GitHub to fetch private packages, run the following command to download them all 
to run the project locally.

```
yarn install
```

### Give it a go!

As long as install command was executed you have everything in place to start the project. Simply run the following 
code to spin-up a development environment on your machine.

```
yarn frontend:develop
```

Under a minute you should have your instance up and running under http://0.0.0.0:8000/ 
**Remember,** if you want to use gatsby.js package with local instance of a data provider, run backend instructions in 
the first place.

### Step-by-step

See below for a cheatsheet of all items above:

1. Navigate to `/`
2. Ensure `STATIK_REGISTRY_TOKEN` is set in environmental variables
3. Run all commands combined into a one-liner

```
yarn cache clean && yarn install && gatsby develop
```

## 🎛 Getting Started with a WordPress backend

### Backend authentication

Docker images are stored within a private space `@statik-space` of ghcr.io registry, which requires authentication for 
fetching dependencies. To log-in enter the following command in the console.

```
docker login https://ghcr.io/
```

Enter your github username and **Personal Access Token** as a password. Do not use your real password.

### Backend using `/commons` directory

WordPress uses some shared dependencies between WordPress and Gatsby. They are all located in `/commons` directory. 
Any time file is updated within this directory be sure to run `yarn backend:build` in a `/` directory.

### Backend docker spin-up

If you run WordPress instance for the first time, you need to install WordPress by typing the following command out 
of the root directory.

```
yarn backend:docker
```

The command should create `.wordpress` directory with all WordPress files.

**@todo:** `wp-config.php` file generation will be handled by Statik CLI, however for now you need to run this command 
in order to make the stack work properly. Be sure to update `{project_name}`, `{s3_token}` with an actual project name.

```
statik-wp config set WP_DEFAULT_THEME "statik" --raw --allow-root && statik-wp config set AS3CF_SETTINGS "serialize(['domain' => 'cloudfront', 'cloudfront' => '{project_name}-assets.statik-stg.space', 'provider' => 'aws', 'use-server-roles' => true,  'bucket' => '{project_name}-assets.statik-stg.space', 'region' => 'eu-west-2', 'copy-to-s3' => true, 'serve-from-s3' => true, 'remove-local-file' => true])" --raw --allow-root && statik-wp config set STATIK_GUTENBERG_SETTINGS "['blocks' => ['value' => 'ssr'],'ssr_blocks' => ['value' => 'core/audio, core/code, core/file, core/gallery, core/heading, core/html, core/image, core/list, core/paragraph, core/buttons, core/preformatted, core/pullquote, core/quote, core/separator, core/spacer, core/table, core/verse, statik/icon, statik/spacer']]" --raw --allow-root && statik-wp config set STATIK_FRONTEND_URL "http://0.0.0.0:8000" --raw --allow-root && statik-wp config set STATIK_PLUGINS_TOKEN "{s3_token}" --raw --allow-root && statik-wp config set STATIK_PLUGINS_URL "https://plugins.statik-stg.app/api/plugins/list" --raw --allow-root && statik-wp config set STATIK_BLOCKS_SETTINGS "['disabled_blocks' => ['value' => ['core-embed/amazon-kindle','core-embed/animoto','core-embed/cloudup','core-embed/collegehumor','core-embed/crowdsignal','core-embed/dailymotion','core-embed/facebook','core-embed/flickr','core-embed/hulu','core-embed/imgur','core-embed/instagram','core-embed/issuu','core-embed/kickstarter','core-embed/meetup-com','core-embed/mixcloud','core-embed/polldaddy','core-embed/reddit','core-embed/reverbnation','core-embed/screencast','core-embed/scribd','core-embed/slideshare','core-embed/smugmug','core-embed/soundcloud','core-embed/speaker','core-embed/speaker-deck','core-embed/spotify','core-embed/ted','core-embed/tiktok','core-embed/tumblr','core-embed/twitter','core-embed/videopress','core-embed/vimeo','core-embed/wordpress','core-embed/wordpress-tv','core-embed/youtube','core/archives','core/calendar','core/categories','core/embed','core/freeform','core/html','core/latest-comments','core/latest-posts','core/missing','core/more','core/nextpage','core/rss','core/search','core/shortcode','core/social-link','core/social-links','core/spacer','core/subhead','core/tag-cloud','core/text-columns','core/video']]]" --raw --allow-root
```

### Backend structure

The `/backend` directory is equal to `/wp-content` directory in regular WordPress instance. Docker is responsible to map
those directories.

The `/backend/mu-plugins` is restricted Statik Infrastructure directory and making any changes in there are 
**STRONGLY NOT RECOMMENDED**, because this may create issues in the Frontend application.

### Create alias for WordPress CLI

WordPress CLI is super useful tool which provides a command-line interface for many actions you might perform in the 
WordPress admin. As with Statik you would use dockerised version of WordPress, you would likely use super long syntax 
for performing CLI queries like

```
docker run -it --rm --volumes-from statik_wordpress --network container:statik_wordpress wordpress:cli plugins list
```

To make it a little bit shorter, you can define an alias by typing

```
alias statik-wp="docker run -it --rm --volumes-from statik_wordpress --network container:statik_wordpress wordpress:cli"
```

From this point, you would be in position to use short hand syntax for all wp-cli commands like

```
statik-wp plugin activate --all
```

### Watch WordPress theme updates

Any changes applied to scss files located in WordPress theme need to be compiled into an output file. In order to watch 
changes and run a job to process raw files navigate to project's root directory and run

```
yarn backend:develop
```

## 📝 Licence

The Statik Boilerplate is licensed under the GPLv3 or later.
