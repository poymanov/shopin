<?php

namespace common\widgets\MainMenu;

use common\models\Category;

class MainMenu extends \yii\bootstrap\Widget
{
    public function run()
    {
        return $this->render('menu');
    }
}