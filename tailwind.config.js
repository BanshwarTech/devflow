import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    navy: '#0B1D3A',
                    blue: '#0D47A1',
                    royal: '#2563EB',
                    sky: '#00A3FF',
                    light: '#E6F2FF',
                },
            },
        },
    },

    plugins: [forms],
};
