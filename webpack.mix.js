let mix = require('laravel-mix');
let build = require('./tasks/build.js');

require('laravel-mix-purgecss');
require('laravel-mix-tailwind');

mix.disableSuccessNotifications();
mix.setPublicPath('source/assets/build/');
mix.webpackConfig({
    plugins: [
        build.jigsaw,
        build.browserSync(),
        build.watch([
            'config.php',
            'source/**/*.md',
            'source/**/*.php',
            'source/**/*.scss',
        ]),
    ],
});

mix.js('source/_assets/js/main.js', 'js')
    .sourceMaps()
    .sass('source/_assets/sass/main.scss', 'css/main.css')
    .sourceMaps()
    .tailwind('source/_assets/js/tailwind.config.js')
    .options({
        processCssUrls: false,
    })
    .purgeCss({
        extensions: ['html', 'md', 'js', 'php', 'vue'],
        folders: ['source'],
        whitelistPatterns: [/language/, /hljs/],
    })
    .version();
