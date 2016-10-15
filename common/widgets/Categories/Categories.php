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

        // Проверяем кэш виджета
        $html = Yii::$app->cache->get('categories');

        // Если кэш отсутствует, делаем запрос
        if (!$html) {
            $categories = Category::find()->with('childCategories')->all();

            $html = $this->renderCategories($categories);

            // Устанавливаем кэш на час
            Yii::$app->cache->set('categories', $html, 3600);
        }

        return $html;
    }

    protected function renderCategories($categories) {
        ob_start();
        include __DIR__ . '/views/categories.php';
        return ob_get_clean();
    }
}