# Core

Este paquete contiene las funciones base del sistema, como autenticación, traducciones, gestor de ficheros, etc.

This package contains the basic functions of the system, such as authentication, translations, file manager, etc.


### Important this package only can install over laravel inertia new instalation with

```* laravel new {project_name} --9.19.* --jet```

## Installation (Required)

### Install
```
php artisan core:install
```

### NPM dependencies (Required)

```
npm install vuex@next
npm install @vueuse/core
npm install vue-inline-svg@next
npm install vue-screen@next
npm install @inertiajs/progress
npm install sass

npm i vuex@next @vueuse/core vue-inline-svg@next vue-screen@next @inertiajs/progress sass
```

### Vendor Publish
```
// The web site report issues 
php artisan vendor:publish --tag=core
```

##### Vendor tags:

`core, core-js, core-lang, core-config`

[core]: incluye todos los tags | Include all tags

## Using this package

### JS

Puede personalizar las vistas en la ruta

You can customize the views on the route

`resources/js/Pages/Core`

##### otros archivos js | another js files

`resources/js/Core`

### Views

`resources/views/Vendor/Core`

## Uninstall
```
php artisan core:uninstall
```

## Deploy
```
APP_NAME=
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain

DB_HOST=db-wepa-team-do-user-12603780-0.b.db.ondigitalocean.com
DB_PORT=25060
DB_DATABASE=db_name
DB_USERNAME=us_name
DB_PASSWORD=*******

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=dae5014ececf41
MAIL_PASSWORD=ae9d06a26f72f0
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@admin.com
MAIL_FROM_NAME=${APP_NAME}

DO_ACCESS_KEY_ID=DO00GGTV2XUMEW2ACWZM
DO_SECRET_ACCESS_KEY=sEK9UkF5P2eGCNVgMJWMDn0qh5b0p1J8x1VPKZwUuSk
DO_DEFAULT_REGION=ams3
DO_BUCKET=wepaspace
DO_ENDPOINT=https://ams3.digitaloceanspaces.com
DO_ROOT=mirametv

```
