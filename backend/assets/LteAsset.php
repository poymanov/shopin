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
        'admin/plugins/font-awesome/font-awesome.min.css',
        'admin/plugins/ionicons/ionicons.min.css',
        'admin/dist/css/AdminLTE.min.css',
        'admin/dist/css/skins/_all-skins.min.css',
        'admin/plugins/iCheck/flat/blue.css',
        'admin/plugins/morris/morris.css',
        'admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
        'admin/plugins/datepicker/datepicker3.css',
        'admin/plugins/daterangepicker/daterangepicker.css',
        'admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
        'admin/plugins/iCheck/square/blue.css'
    ];

    public $js = [
        'admin/plugins/jQueryUI/jquery-ui.min.js',
        'admin/plugins/raphael/raphael-min.js',
        //'admin/plugins/morris/morris.min.js',
        'admin/plugins/sparkline/jquery.sparkline.min.js',
        'admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'admin/plugins/knob/jquery.knob.js',
        //'admin/plugins/daterangepicker/moment.min.js',
        //'admin/plugins/daterangepicker/daterangepicker.js',
        //'admin/plugins/datepicker/bootstrap-datepicker.js',
        'admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
        'admin/plugins/slimScroll/jquery.slimscroll.min.js',
        'admin/plugins/fastclick/fastclick.js',
        'admin/dist/js/app.min.js',
        //'admin/dist/js/pages/dashboard.js',
        'admin/dist/js/demo.js',
        'admin/plugins/iCheck/icheck.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}