const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.autoload({
    jquery: ['$', 'global.jQuery']
});
mix.styles(['resources/assets/css/app.css',
    'resources/assets/css/bootstrap.css',
    'resources/assets/css/style.css',
    'resources/assets/css/style2.css'], 'public/css/all.css')
    .js(['resources/assets/js/popper.js',
        'resources/assets/js/app.js',
        'resources/assets/js/bootstrap.js',
        //'resources/assets/js/jquery.js',
        ////////////////////////////////
        //////////////////////////////////////////////
        'resources/assets/js/PICObuilder/PICObuilder.js',
        'resources/assets/js/PICObuilder/AccordionTooltip.js',
        'resources/assets/js/PICObuilder/baseurl.js',
        'resources/assets/js/PICObuilder/changeseeker.js',
        'resources/assets/js/PICObuilder/commons.js',
        'resources/assets/js/PICObuilder/commonschange.js',
        'resources/assets/js/PICObuilder/commonsdecs.js',
        'resources/assets/js/PICObuilder/debug.js',
        'resources/assets/js/PICObuilder/decslanguages.js',
        'resources/assets/js/PICObuilder/decsmanager.js',
        'resources/assets/js/PICObuilder/localepreservedata.js',
        'resources/assets/js/PICObuilder/hideshow.js',
        'resources/assets/js/PICObuilder/infomessage.js',
        'resources/assets/js/PICObuilder/init.js',
        'resources/assets/js/PICObuilder/initfunctions.js',
        'resources/assets/js/PICObuilder/languagetoggler.js',
        'resources/assets/js/PICObuilder/loadingrequest.js',
        'resources/assets/js/PICObuilder/newquerybuild.js',
        'resources/assets/js/PICObuilder/resultsmanager.js',
        'resources/assets/js/PICObuilder/translator.js'], 'public/js/all.js').version();

mix.autoload({
    jquery: ['$', 'global.jQuery']
});
mix.styles(['resources/assets/css/bootstrap.css',
    'resources/assets/css/dataTables.css',
    'resources/assets/css/logstyle.css'], 'public/css/log.css')
    .js(['resources/assets/js/jquery.js',
        'resources/assets/js/bootstrap.js',
        'resources/assets/js/dataTables.min.js',
        'resources/assets/js/font-awesome.all.js',
        ////////////////////////////////
        //////////////////////////////////////////////
        'resources/assets/js/AdvancedLog/AdvancedLog.js',
        'resources/assets/js/AdvancedLog/init.js',
        'resources/assets/js/AdvancedLog/initfunctions.js'], 'public/js/log.js').version();
