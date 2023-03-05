const defaultTheme = require("tailwindcss/defaultTheme");

function withOpacity(variableName) {
    return ({opacityValue}) => {
        if (opacityValue !== undefined) {
            return `rgba(var(${variableName}), ${opacityValue})`;
        }
        return `rgb(var(${variableName}))`;
    };
}

/** @type {import("tailwindcss").Config} */
module.exports = {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue"
    ],
    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: "2rem",
                sm: "2rem",
                md: "2rem",
                lg: "4rem",
                xl: "5rem",
                "2xl": "6rem"
            }
        },
        extend: {
            spacing: {
                '1/2': '50%',
                '1/3': '33.333333%',
                '2/3': '66.666667%',
                '1/4': '25%',
                '2/4': '50%',
                '3/4': '75%',
                '1/5': '20%',
                '2/5': '40%',
                '3/5': '60%',
                '4/5': '80%',
                '1/6': '16.666667%',
                '2/6': '33.333333%',
                '3/6': '50%',
                '4/6': '66.666667%',
                '5/6': '83.333333%',
                '1/12': '8.333333%',
                '2/12': '16.666667%',
                '3/12': '25%',
                '4/12': '33.333333%',
                '5/12': '41.666667%',
                '6/12': '50%',
                '7/12': '58.333333%',
                '8/12': '66.666667%',
                '9/12': '75%',
                '10/12': '83.333333%',
                '11/12': '91.666667%',
            },
            colors: {
                skin: {
                    base: 'var(--color-base)',
                    muted: 'var(--color-muted)',
                    inverted: 'var(--color-inverted)',
                    fill: 'var(--color-fill)',
                    accent: 'var(--color-accent)',
                    "accent-hover": 'var(--color-accent-hover)',
                    background: 'var(--color-background)',
                    foreground: 'var(--color-foreground)',
                    light: 'var(--color-light)',
                    dark: 'var(--color-dark)',
                    success: 'var(--color-success)',
                    info: 'var(--color-info)',
                    warning: 'var(--color-warning)',
                    danger: 'var(--color-danger)',
                    primary: {
                        DEFAULT: withOpacity("--color-primary-500"),
                        900: withOpacity("--color-primary-900"),
                        800: withOpacity("--color-primary-800"),
                        700: withOpacity("--color-primary-700"),
                        600: withOpacity("--color-primary-600"),
                        500: withOpacity("--color-primary-500"),
                        400: withOpacity("--color-primary-400"),
                        300: withOpacity("--color-primary-300"),
                        200: withOpacity("--color-primary-200"),
                        100: withOpacity("--color-primary-100")
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
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography")
    ]
};
