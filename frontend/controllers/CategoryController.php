<?php
namespace frontend\controllers;

use common\models\Product;
use common\models\ProductBrand;
use common\models\ProductType;
use yii\web\Controller;
use common\models\Category;
use common\models\ProductDiscount;
use yii\data\Pagination;

/**
 * Product controller
 */
class CategoryController extends Controller
{

    public function actionIndex($slug)
    {

        // Поиск категории по slug, иначе ошибка

        $category = Category::find()->where(['slug' => $slug])->one();

        /**
         * Возвращаем 404 ошибку, если товар не существует или неактивен
         */
        if (empty($category)) {
            throw new \yii\web\HttpException('404','Категория не существует');
            return;
        }

        // Получение типов скидок товаров
        $discounts = ProductDiscount::find()->all();

        // Получение типов товаров
        $types = ProductType::find()->all();

        // Получение брендов товаров
        $brands = ProductBrand::find()->all();

        // Получение запроса товаров категории
        $query = $category->getAllProducts('query');

        // Настройки пагинации
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 9]);
        $products = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', [
            'category' => $category,
            'discounts' => $discounts,
            'types' => $types,
            'brands' => $brands,
            'products' => $products,
            'pages' => $pages
        ]);

    }
}
