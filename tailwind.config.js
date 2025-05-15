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
            'edelweiss-brown': '#A0522D',  // Marrón tierra principal
            'edelweiss-light-brown': '#D2B48C', // Marrón tierra más claro
            'edelweiss-flower': '#FFFFFF',    // Blanco de la flor
            'edelweiss-green': '#8FBC8F',    // Verde hoja
            // ... otros colores de tu paleta
        },
        },
    },

    plugins: [forms],
};
