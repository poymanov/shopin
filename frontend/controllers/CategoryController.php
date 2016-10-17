<?php
namespace frontend\controllers;

use common\models\Product;
use yii\web\Controller;
use common\models\Category;
use yii\data\Pagination;
use Yii;

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

        // Получение запроса товаров категории
        $query = $category->getAllProducts('query');

        // Получение параметров из get-запроса, если нужно применить фильтрацию
        $get = Yii::$app->request->get();

        // Получение списка параметров для фильтрации по свойствам товаров
        $discountsWhere = [];
        $typesWhere = [];
        $brandsWhere = [];

        foreach ($get as $key => $value) {
            if (strpos($key, "discount") !== false) {
                $discountsWhere[] = $value;
            } elseif (strpos($key, "type") !== false) {
                $typesWhere[] = $value;
            } elseif (strpos($key, "brand") !== false) {
                $brandsWhere[] = $value;
            }
        }

        // Фильтруем товары согласно свойствам
        if ($discountsWhere) {
            $query->andWhere(['discount_id' => $discountsWhere]);
        }

        if ($typesWhere) {
            $query->andWhere(['type_id' => $typesWhere]);
        }

        if ($brandsWhere) {
            $query->andWhere(['brand_id' => $brandsWhere]);
        }

        // Настройки пагинации
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 9]);
        $products = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', [
            'category' => $category,
            'products' => $products,
            'pages' => $pages,
            'discountsWhere' => $discountsWhere,
            'typesWhere' => $typesWhere,
            'brandsWhere' => $brandsWhere
        ]);

    }
}
