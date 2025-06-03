var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('./public/')
    .setPublicPath('/bundles/syliushappycmsplugin/')
    .setManifestKeyPrefix('')

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .disableSingleRuntimeChunk()
    .enableSassLoader()
    .enableVueLoader()
    .enablePostCssLoader()
    .enableStimulusBridge('./assets/admin/controllers.json')

    .copyFiles({
        from: './assets/media/dist',
        to: 'dist/[path][name].[ext]',
    })

    .addEntry('flexible-content', './assets/js/flexible-content.js')
    .addEntry('media-form', './assets/media/js/app.js')
    .addEntry('seo-block-type', './assets/blocks/seo-block-type.js')
    .addEntry('accordion-block-type', './assets/blocks/accordion-block-type.js')
    .addEntry('stimulus-happycsms', './assets/admin/entrypoint.js')
;

module.exports = Encore.getWebpackConfig();
