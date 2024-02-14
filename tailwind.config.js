import defaultTheme from 'tailwindcss/defaultTheme';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        '../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        '../storage/framework/views/*.php',
        '../resources/views/**/*.blade.php',
        '../app/UI/Views/**/*.blade.php',
        '../app/UI/Components/*.blade.php',
        '../app/UI/components/*.blade.php',
        '../app/UI/components/layout/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        typography
    ],

};
