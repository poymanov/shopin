<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css',
        'admin/dist/css/AdminLTE.min.css',
        'admin/dist/css/skins/_all-skins.min.css',
        'admin/plugins/iCheck/flat/blue.css',
        'admin/plugins/morris/morris.css',
        'admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
        'admin/plugins/datepicker/datepicker3.css',
        'admin/plugins/daterangepicker/daterangepicker.css',
        'admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'
    ];

    public $js = [
        'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
        'admin/plugins/morris/morris.min.js',
        'admin/plugins/sparkline/jquery.sparkline.min.js',
        'admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'admin/plugins/knob/jquery.knob.js',
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js',
        'admin/plugins/daterangepicker/daterangepicker.js',
        'admin/plugins/datepicker/bootstrap-datepicker.js',
        'admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
        'admin/plugins/slimScroll/jquery.slimscroll.min.js',
        'admin/plugins/fastclick/fastclick.js',
        'admin/dist/js/app.min.js',
        'admin/dist/js/pages/dashboard.js',
        'admin/dist/js/demo.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}