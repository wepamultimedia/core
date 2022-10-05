const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import("tailwindcss").Config} */
module.exports = {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php", "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php", "./resources/js/**/*.vue", "./node_modules/tw-elements/dist/js/**/*.js"
    ],
    theme: {
        extend: {
            colors: {
                skin: {
                    base: "var(--color-base)",
                    'base-dark': "var(--color-base-dark)",
                    muted: "var(--color-muted)",
                    'muted-dark': "var(--color-muted-dark)",
                    inverted: "var(--color-inverted)",
                    'inverted-dark': "var(--color-inverted-dark)",
                    fill: "var(--color-fill)",
                    'fill-dark': "var(--color-fill-dark)",
                    accent: "var(--color-accent)",
                    'accent-hover': "var(--color-accent-hover)",
                    'accent-dark': "var(--color-accent-dark)",
                    'accent-hover-dark': "var(--color-accent-hover-dark)",
                    background: "var(--color-background)",
                    'background-dark': "var(--color-background-dark)",
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
            },
            fontFamily: {
                sans: [
                    "Nunito", ...defaultTheme.fontFamily.sans
                ]
            }
        }
    },

    plugins: [
        require("@tailwindcss/forms"), require("@tailwindcss/typography")
    ]
};
