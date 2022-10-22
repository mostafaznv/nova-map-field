const mix = require('laravel-mix')
const path = require('path')

mix.setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .sass('resources/scss/field.scss', 'css')
    .vue({version: 3})
    .webpackConfig({
        externals: {
            vue: 'Vue',
        },
        output: {
            uniqueName: 'mostafaznv/nova-map-field'
        }
    })
    .alias({
        'laravel-nova': path.join(
            __dirname,
            '../../vendor/laravel/nova/resources/js/mixins/packages.js'
        )
    })
    .copy('./images', 'dist/vendor/nova-map-field/dist/images')
