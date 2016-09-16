<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/style4.css',
        'css/chocolate.css',
        'css/jstarbox.css',
        'css/popuo-box.css'
    ];
    public $js = [
        'js/simpleCart.min.js',
        'js/jquery.chocolat.js',
        'js/jstarbox.js',
        'js/jquery.magnific-popup.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
