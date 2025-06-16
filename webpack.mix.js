const mix = require("laravel-mix");
const path = require("path");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications(); // Solve error on M1
mix.js("resources/js/app.js", "public/js")
    // .extract()
    .vue(3)
    .postCss("resources/css/app.css", "public/css", [
        //
    ])
    .webpackConfig({
        resolve: {
            alias: {
                "@": path.resolve("resources/js"),
                // ziggy: path.resolve("vendor/tightenco/ziggy/dist/js/route.js"),
            },
        },
    })
    .sourceMaps({ devType: "inline-source-map" })
    .version();
