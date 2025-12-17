const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css', {
        sassOptions: {
            quietDeps: true,
        }
    })
    .options({
        postCss: [
            require('autoprefixer'),
        ],
    })

    .sourceMaps()
    .version();

mix.webpackConfig({
    stats: {
        children: true,
    },
    module: {
        rules: [
            {
                test: /\.(png|jpe?g|gif|webp)$/i,
                type: 'asset/resource',
            }
        ]
    }
});