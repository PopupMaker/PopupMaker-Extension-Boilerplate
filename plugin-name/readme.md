# {PLUGIN_NAME}

{PLUGIN_NAME} is a plugin created by Code Atlantic.

## Getting Started

### Downloading And Using As A Plugin

To use this plugin, this repo can be downloaded as a zip and installed as-is as a WordPress plugin. Once installed and activated, Go to wp-admin > Appearance > Menus and edit your menu.

### Getting Set Up For Development

### Requirements

-   Composer
-   Node >=18 / NPM

### Install & use appropriate version of Node.js via NVM

```bash
nvm use
```

### Install project dependencies

```bash
# install deps
npm i && composer install
```

### Build assets

```bash
npm run build
```

### Start asset watcher & build routines

```bash
npm run start # Normal
npm run start:hot # Hot module loading via @wordpress/env & Gutenberg plugin.
```

### Optionally install `wp-env` dev environment

This allows very quick creation of a working development environment using docker.

`npm i -g @wordpress/env`

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the releases in this repository.

## License

This project is licensed under the GPLv3 License.