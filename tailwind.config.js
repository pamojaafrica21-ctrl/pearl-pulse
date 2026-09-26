import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            colors: {
                forest: {
                    DEFAULT: '#1c2b1f',
                    light: '#2a3d2e',
                },
                sand: {
                    DEFAULT: '#e8dfd0',
                    deep: '#d4c4a8',
                },
                cream: '#f7f4ef',
                beige: '#e6ddd0',
                brown: '#c2ab92',
                surface: '#ffffff',
                charcoal: '#1a1a18',
                gold: '#8a7a5c',
                muted: '#5c5c54',
            },
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
                display: ['Cormorant Garamond', ...defaultTheme.fontFamily.serif],
            },
        },
    },

    plugins: [forms, typography],
};
