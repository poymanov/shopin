<?php

namespace common\widgets\MainMenu;

use common\models\Category;

class MainMenu extends \yii\bootstrap\Widget
{
    public function run()
    {
        // Получаем список родительских категорий

        $categories = Category::getParentsCategory();

        return $this->render('menu', ['categories' => $categories]);
    }
}