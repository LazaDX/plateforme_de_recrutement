const mix = require('laravel-mix');
const webpack = require('webpack');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require("tailwindcss"),
    ]);

mix.webpackConfig({
    plugins: [
        new webpack.DefinePlugin({
            __VUE_OPTIONS_API__: JSON.stringify(true),
            __VUE_PROD_DEVTOOLS__: JSON.stringify(false),
        }),
    ],
});

mix.js('resources/js/frontOffice/vue/navbarApp.js', 'public/js');


mix.options({
    processCssUrls: false
});
