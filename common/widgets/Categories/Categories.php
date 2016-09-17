<?php

namespace common\widgets\Categories;

use Yii;
use common\widgets\Categories\CategoriesAsset;
use common\models\Category;

class Categories extends \yii\bootstrap\Widget
{
    public function init() {
        parent::init();

        $view = $this->getView();
        CategoriesAsset::register($view);
    }

    public function run()
    {
        $categories = Category::find()->all();
        
        return $this->render('categories', compact(['categories']));
    }
}