# Core

Este paquete contiene las funciones base del sistema, como autenticación, traducciones, gestor de ficheros, etc.

This package contains the basic functions of the system, such as authentication, translations, file manager, etc.

## Installation

### Vendor Publish
```
php artisan vendor:publish --tag=core
php artisan vendor:publish --tag=core-overwrite --force
```
Es necesario el force porque tenemos que sobreescribir ficheros que pertenecen a Laravel:

The force is necessary because we have to overwrite files that belong to Laravel:

`resources/js/app.js`

##### Vendor tags:

`core, core-js, core-lang, core-config`

core: incluye todos los tags | Include all tags


## Using this package

### JS
Puede personalizar las vistas en la ruta

You can customize the views on the route

`resources/js/Pages/Vendor/Core`

##### otros archivos js | another js files
`resources/js/Vendor/Core`

### Views
`resources/views/Vendor/Core`
