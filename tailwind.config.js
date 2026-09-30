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
                    DEFAULT: '#254520',
                    light: '#335525',
                },
                sand: {
                    DEFAULT: '#ebe4d4',
                    deep: '#d5c3a3',
                },
                cream: '#f5f2eb',
                beige: '#e6dbc1',
                brown: '#c3aa84',
                surface: '#ffffff',
                charcoal: '#1c1b1f',
                gold: '#a8926a',
                muted: '#6b6560',
            },
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
                display: ['Cormorant Garamond', ...defaultTheme.fontFamily.serif],
            },
        },
    },

    plugins: [forms, typography],
};
