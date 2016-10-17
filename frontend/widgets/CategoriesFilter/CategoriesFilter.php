<?php

namespace frontend\widgets\CategoriesFilter;

use Yii;
use common\models\Product;
use common\models\ProductBrand;
use common\models\ProductType;
use common\models\ProductDiscount;

class CategoriesFilter extends \yii\bootstrap\Widget
{
    public $category;
    public $discountsWhere;
    public $typesWhere;
    public $brandsWhere;

    public function run()
    {

        // Получение типов скидок товаров
        $discounts = ProductDiscount::find()->all();

        // Получение типов товаров
        $types = ProductType::find()->all();

        // Получение брендов товаров
        $brands = ProductBrand::find()->all();
        
        return $this->render('filter', [
            'discounts' => $discounts,
            'types' => $types,
            'brands' => $brands,
            'discountsWhere' => $this->discountsWhere,
            'typesWhere' => $this->typesWhere,
            'brandsWhere' => $this->brandsWhere,            
            'category' => $this->category
        ]);

    }
}