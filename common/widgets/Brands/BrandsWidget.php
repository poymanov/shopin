<?php

namespace common\widgets\Brands;

use Yii;
use common\models\Brand;

class BrandsWidget extends \yii\bootstrap\Widget
{
    public function run()
    {

        $html = Yii::$app->cache->get('brands');

        if(!$html) {

            $brands = Brand::find()->where(['status'=> '1'])->orderBy(['sort' => 'ASC'])->all();

            $html = $this->renderBrands($brands);

            Yii::$app->cache->set('brands', $html, 86400);
        }

        return $html;
    }

    protected function renderBrands($brands) {
        ob_start();
        include __DIR__ . '/views/brands.php';
        return ob_get_clean();
    }
}