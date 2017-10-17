var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .addEntry('requires', './assets/js/requires.js')
    .addEntry('adminlte-js', './assets/js/adminlte.min.js')
    .addStyleEntry('global', './assets/css/global.scss')
    .addStyleEntry('adminlte', './assets/css/AdminLTE.min.css')
    .addStyleEntry('adminlte-skin', './assets/css/skins/skin-black-light.min.css')
    .enableSassLoader()
    .autoProvidejQuery()
    .enableSourceMaps(true)
;

module.exports = Encore.getWebpackConfig();
