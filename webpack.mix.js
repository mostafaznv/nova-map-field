const mix = require('laravel-mix')
const path = require('path')
const webpack = require("webpack")


mix.setPublicPath('dist')
    .js('resources/js/field.js', '')
    .sass('resources/scss/field.scss', '')
    .vue({
        version: 3
    })
    .webpackConfig({
        externals: {
            vue: 'Vue',
        },
        output: {
            uniqueName: 'mostafaznv/nova-map-field'
        },
        plugins: [
            new webpack.optimize.LimitChunkCountPlugin({
                maxChunks: 1,
            }),
        ],
        module: {
            rules: [
                {
                    test: /\.m?js/,
                    resolve: {
                        fullySpecified: false
                    }
                },
            ],
        },
    })
    .alias({
        'laravel-nova': path.join(
            __dirname,
            '../../../vendor/laravel/nova/resources/js/mixins/packages.js'
        )
    })
    .copy('./images', 'dist/vendor/nova-map-field/dist/images')


if (mix.inProduction()) {
    mix.version()
}
