const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            // tes extensions ici
        },
    },

    plugins: [],
};
