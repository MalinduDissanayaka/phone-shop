import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

// Semantic colors resolve to CSS variables defined in resources/css/app.css,
// so a single class (e.g. `bg-surface`) adapts to light and dark mode.
const token = (name) => `rgb(var(--c-${name}) / <alpha-value>)`;

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                canvas: token('canvas'),
                surface: {
                    DEFAULT: token('surface'),
                    muted: token('surface-muted'),
                },
                field: token('field'),
                line: {
                    DEFAULT: token('line'),
                    strong: token('line-strong'),
                },
                fg: {
                    DEFAULT: token('fg'),
                    soft: token('fg-soft'),
                    muted: token('fg-muted'),
                    subtle: token('fg-subtle'),
                },
                primary: token('primary'),
                accent: token('accent'),
                link: token('link'),
                success: token('success'),
                warning: token('warning'),
                danger: token('danger'),
            },
        },
    },

    plugins: [forms],
};
