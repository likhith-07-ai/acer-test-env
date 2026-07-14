import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#f2f2f2',
                    100: '#e6e6e6',
                    200: '#cccccc',
                    300: '#b3b3b3',
                    400: '#808080',
                    500: '#0a0a0a',
                    600: '#090909',
                    700: '#080808',
                    800: '#060606',
                    900: '#040404',
                    950: '#020202',
                    DEFAULT: '#0a0a0a',
                },
                secondary: {
                    50: '#f5f5f5',
                    100: '#e5e5e5',
                    200: '#d4d4d4',
                    300: '#a3a3a3',
                    400: '#737373',
                    500: '#171717',
                    600: '#111111',
                    700: '#0d0d0d',
                    800: '#080808',
                    900: '#050505',
                    950: '#030303',
                    DEFAULT: '#171717',
                },
                tertiary: {
                    50: '#fafafa',
                    100: '#f5f5f5',
                    200: '#e5e5e5',
                    300: '#d4d4d4',
                    400: '#a3a3a3',
                    500: '#262626',
                    600: '#1f1f1f',
                    700: '#171717',
                    800: '#0f0f0f',
                    900: '#0a0a0a',
                    950: '#050505',
                    DEFAULT: '#262626',
                },
                quaternary: {
                    50: '#f9f9f9',
                    100: '#f0f0f0',
                    200: '#e0e0e0',
                    300: '#bdbdbd',
                    400: '#9e9e9e',
                    500: '#404040',
                    600: '#333333',
                    700: '#262626',
                    800: '#1a1a1a',
                    900: '#0d0d0d',
                    950: '#050505',
                    DEFAULT: '#404040',
                },
                quinary: {
                    50: '#fcfcfc',
                    100: '#f5f5f5',
                    200: '#eeeeee',
                    300: '#e0e0e0',
                    400: '#bdbdbd',
                    500: '#525252',
                    600: '#424242',
                    700: '#303030',
                    800: '#1f1f1f',
                    900: '#121212',
                    950: '#080808',
                    DEFAULT: '#525252',
                }
            },
            fontFamily: {
                sans: ['Satoshi', ...defaultTheme.fontFamily.sans],
                caladea: ['Caladea', 'Georgia', 'serif'],
            },
        },
    },

    plugins: [forms],
};
