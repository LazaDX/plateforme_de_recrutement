const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        
    ],

    theme: {
        extend: {
           
        },
    },

    plugins: [],
};resources/views/frontOffice/components/navbar.blade.php
