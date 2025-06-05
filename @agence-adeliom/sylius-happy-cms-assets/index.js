const path = require('path');
const Encore = require('@symfony/webpack-encore');

class SyliusHappyCMSPlugin {
    /**
     * Provide a light Webpack configuration for Sylius Admin
     * All the stimulus stuff should be handled by the app.admin entrypoint
     */
    static getBaseWebpackConfig(rootDir) {
        this._prepareWebpackConfig(rootDir);

        Encore
            .addEntry('admin-entry', path.resolve(__dirname, 'Resources/assets/entrypoint.js'));

        const adminConfig = Encore.getWebpackConfig();

        adminConfig.externals = { ...adminConfig.externals, window: 'window', document: 'document' };
        adminConfig.name = 'happycms-admin';

        Encore.reset();

        return adminConfig;
    }

    /**
     * For a ready-to-use Stimulus bridge. Should be used only for sylius/sylius tests
     * For instances started with Sylius-Standard < 2.0.4, it'll still be used unless upgrading webpack.config.js
     * to use the method above getBaseWebpackConfig()
     */
    static getWebpackConfig(rootDir) {
        this._prepareWebpackConfig(rootDir);

        Encore
            .addEntry('admin-entry', path.resolve(__dirname, 'Resources/assets/app.js'))
            .enableStimulusBridge(path.resolve(__dirname, 'Resources/assets/controllers.json'));

        const adminConfig = Encore.getWebpackConfig();

        adminConfig.externals = { ...adminConfig.externals, window: 'window', document: 'document' };
        adminConfig.name = 'happycms-admin';

        Encore.reset();

        return adminConfig;
    }

    static _prepareWebpackConfig(rootDir) {
        Encore
            .setOutputPath('public/build/happycms/')
            .setPublicPath('/build/happycms')
            .disableSingleRuntimeChunk()
            .cleanupOutputBeforeBuild()
            .enableSourceMaps(!Encore.isProduction())
            .enableVersioning(Encore.isProduction())
            .enableSassLoader((options) => {
                // eslint-disable-next-line no-param-reassign
                options.additionalData = `$rootDir: '${rootDir}';`;
            });
    }
}

module.exports = SyliusHappyCMSPlugin;