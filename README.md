# Core

Este paquete contiene las funciones base del sistema, como autenticación, traducciones, gestor de ficheros, etc.

This package contains the basic functions of the system, such as authentication, translations, file manager, etc.

## Installation

### NPM dependencies

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
* php artisan jetstream:install inertia

php artisan vendor:publish --tag=core
php artisan vendor:publish --tag=core-overwrite --force
```

Es necesario el force porque tenemos que sobreescribir ficheros que pertenecen a Laravel:

The force is necessary because we have to overwrite files that belong to Laravel:

```
tailwind.config.js
vite.config.js

resources/js/app.js
resources/css/app.css

Providers/AuthServiceProvider.php
```

O puedes añadir las siguiente lineas a los ficheros:
#### tailwind.config.js
``` 
module.exports = {
    // Add
    darkMode: "class",
    content: [
        
        // Add
        "./resources/views/**/*.vue",
        "./node_modules/tw-elements/dist/js/**/*.js"
    ], 
    // add
    theme: {
        extend: {
            colors: {
                skin: {
                    base: "var(--color-base)", 
                    "base-dark": "var(--color-base-dark)", 
                    muted: "var(--color-muted)", 
                    "muted-dark": "var(--color-muted-dark)", 
                    inverted: "var(--color-inverted)", 
                    "inverted-dark": "var(--color-inverted-dark)", 
                    fill: "var(--color-fill)", 
                    "fill-dark": "var(--color-fill-dark)", 
                    accent: "var(--color-accent)", 
                    "accent-hover": "var(--color-accent-hover)", 
                    "accent-dark": "var(--color-accent-dark)", 
                    "accent-hover-dark": "var(--color-accent-hover-dark)", 
                    background: "var(--color-background)", 
                    "background-dark": "var(--color-background-dark)", 
                    light: "var(--color-light)", 
                    dark: "var(--color-dark)", 
                    success: "var(--color-success)", 
                    info: "var(--color-info)", 
                    warning: "var(--color-warning)", 
                    danger: "var(--color-danger)", 
                    primary: {
                        DEFAULT: "var(--color-primary-500)", 
                        900: "var(--color-primary-900)", 
                        800: "var(--color-primary-800)", 
                        700: "var(--color-primary-700)", 
                        600: "var(--color-primary-600)", 
                        500: "var(--color-primary-500)", 
                        400: "var(--color-primary-400)", 
                        300: "var(--color-primary-300)", 
                        200: "var(--color-primary-200)", 
                        100: "var(--color-primary-100)"
                    }
                }
            }, fontFamily: {
                sans: [
                    "Nunito", ...defaultTheme.fontFamily.sans
                ]
            }
        }
    },
}
```

#### vite.config.js
```
// add
import * as path from "path";

// add
resolve:{
    alias:{
        '@core' : path.resolve(__dirname, './resources/js/core'),
        '@views' : path.resolve(__dirname, './resources/views'),
        '@core/components' : path.resolve(__dirname, './resources/js/core/components'),
        '@core/store' : path.resolve(__dirname, './resources/js/core/store'),
    },
},
```

#### resources/css/app.css
```
// add
@layer base {
    :root {
        --color-primary-100       : #c8c8c9;
        --color-primary-200       : #A2AAAC;
        --color-primary-300       : #767F84;
        --color-primary-400       : #49545B;
        --color-primary-500       : #1C2833;
        --color-primary-600       : #171F28;
        --color-primary-700       : #11171D;
        --color-primary-800       : #0B0E13;
        --color-primary-900       : #050608;

        --color-base              : #1C2833;
        --color-base-dark         : #F8F9F9;
        --color-muted             : #84918B;
        --color-muted-dark        : #84918B;
        --color-inverted          : #F8F9F9;
        --color-inverted-dark     : #1C2833;
        --color-fill              : var(--color-primary-100);
        --color-fill-dark         : var(--color-primary-500);

        --color-light             : #F8F9F9;
        --color-dark              : #1C2833;

        --color-accent            : var(--color-primary-600);
        --color-accent-dark       : var(--color-primary-100);
        --color-accent-hover      : var(--color-primary-700);
        --color-accent-hover-dark : white;
        --color-background        : #CFD3D4;
        --color-background-dark   : #11171D;

        --color-success           : #239B56;
        --color-info              : #3498DB;
        --color-warning           : #F39C12;
        --color-danger            : #C0392B;
    }


    .admin-custom {
        --color-primary-100     : #bbbbbb;
        --color-primary-200     : #DAA9B1;
        --color-primary-300     : #C3849E;
        --color-primary-400     : #A96391;
        --color-primary-500     : #8B4584;
        --color-primary-600     : #743175;
        --color-primary-700     : #55205B;
        --color-primary-800     : #35123E;
        --color-primary-900     : #17071E;

        --color-base            : #1C2833;
        --color-base-dark       : #F8F9F9;
        --color-muted           : #84918B;
        --color-muted-dark      : #84918B;
        --color-inverted        : #F8F9F9;
        --color-inverted-dark   : #1C2833;
        --color-fill            : var(--color-primary-100);
        --color-fill-dark       : var(--color-primary-500);

        --color-accent          : var(--color-primary-100);
        --color-accent-hover    : var(--color-primary-200);
        --color-background      : #e7eaea;
        --color-background-dark : #11171D;
    }

    body, button {
        @apply text-skin-base dark:text-skin-base-dark
    }

    h1 {
        @apply text-2xl
    }

    h2 {
        @apply text-xl
    }

    h3 {
        @apply text-lg
    }

}

@layer components {
    .btn {
        @apply inline-flex tracking-wide uppercase text-xs px-4 py-2 rounded-md font-semibold border border-transparent focus:outline-none bg-gray-600 hover:bg-gray-700 active:bg-gray-900 focus:border-gray-700 focus:ring-gray-300 focus:ring disabled:opacity-25 transition flex items-center gap-2
    }

    .btn-success {
        @apply text-white bg-green-600 hover:bg-green-700 active:bg-green-900 focus:border-green-700 focus:ring-green-300
    }

    .btn-info {
        @apply text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-900 focus:border-blue-700 focus:ring-blue-300
    }

    .btn-warning {
        @apply text-white bg-orange-600 hover:bg-orange-700 active:bg-orange-900 focus:border-orange-700 focus:ring-orange-300
    }

    .btn-danger {
        @apply text-white bg-red-600 hover:bg-red-700 active:bg-red-900 focus:border-red-700 focus:ring-red-300
    }

    .btn-neutral {
        @apply text-skin-base hover:text-white bg-transparent hover:bg-gray-400 hover:bg-opacity-50 hover:bg-opacity-50
    }

    .btn-primary {
        @apply text-white bg-skin-primary-600 hover:bg-skin-primary-700 active:bg-skin-primary-900 focus:border-skin-primary-700 focus:ring-skin-primary-300
    }
}
```


#### Providers/AuthServiceProvider.php
```
// add 
use Illuminate\Support\Facades\Gate;

public function boot()
{
    $this->registerPolicies();

    // add
    Gate::before(function ($user, $ability) {
        return $user->hasRole('super.admin') ? true : null;
    });
}
```

#### app/Http/Middleware/Authenticate.php
```
// replace
if (! $request->expectsJson()) {
    return route('login');
}

// for
if (! $request->expectsJson()) {
    if(Str::contains($request->url(), 'admin')){
        return route('admin.login');
    } else {
        return route('login');
    }
}
```

Si ejecuta el overwrite tenga en cuenta que todos los archivos antes mensionados sobreescribiran cualquier cambio que
haya realizado en los mismos

If you run the overwrite be aware that all of the above files will overwrite any changes you have made to them.
you have made to them. 

##### Vendor tags:

`core, core-js, core-lang, core-config, core-overwrite`

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
