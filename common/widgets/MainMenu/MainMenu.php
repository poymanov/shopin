<?php

namespace common\widgets\MainMenu;

use Yii;
use common\widgets\Categories\CategoriesAsset;
use common\models\Category;

class MainMenu extends \yii\bootstrap\Widget
{
    public function run()
    {
        return $this->render('menu');
    }
}