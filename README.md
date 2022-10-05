# Core

Este paquete contiene las funciones base del sistema, como autenticación, traducciones, gestor de ficheros, etc.

This package contains the basic functions of the system, such as authentication, translations, file manager, etc.

## Installation (Required)

### Install
```
php artisan core:install
```

### NPM dependencies (Required)

```
npm install vuex@next
npm install vue-inline-svg@next
npm install vue-screen@next
```

### Vendor Publish
* Important this package only can install over laravel inertia new instalation with

```* laravel new {project_name} --jet```
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

`resources/js/Pages/Vendor/Core`

##### otros archivos js | another js files

`resources/js/Vendor/Core`

### Views

`resources/views/Vendor/Core`

## Uninstall
```
php artisan core:uninstall
```
