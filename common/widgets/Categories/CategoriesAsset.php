<?php

namespace common\widgets\Categories;

use yii\web\AssetBundle;

class CategoriesAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/Categories';

    public $js = [
        'js/script.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}