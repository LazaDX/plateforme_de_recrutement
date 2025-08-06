const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require("tailwindcss"),
    ]);

mix.js('resources/js/frontOffice/vue/navbarApp.js', 'public/js');
mix.js('resources/js/frontOffice/vue/modalFormOfferApp.js', 'public/js')
