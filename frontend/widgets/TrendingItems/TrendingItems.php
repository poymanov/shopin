<?php

namespace frontend\widgets\TrendingItems;

use Yii;
use common\models\Product;

class TrendingItems extends \yii\bootstrap\Widget
{
    public function run()
    {
        // Проверяем кэш виджета
        $html = Yii::$app->cache->get('trendingItems');

        // Если кэш отсутствует, делаем запрос
        if (!$html) {
            $products = Product::find()->where(['status' => 1])
                ->with('productImages', 'allCategories', 'allCategories.category')
                ->orderBy(['id' => 'desc'])->limit(8)->all();

            $html = $this->renderProducts($products);

            // Устанавливаем кэш на час
            Yii::$app->cache->set('trendingItems', $html, 3600);
        }

        return $html;
    }

    protected function renderProducts($products) {
        ob_start();
        include __DIR__ . '/views/items.php';
        return ob_get_clean();
    }
}