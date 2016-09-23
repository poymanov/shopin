<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Product;

/**
 * Product controller
 */
class ProductController extends Controller
{

    public function actionIndex($slug)
    {

        // Поиск товара по slug, иначе ошибка

        $product = Product::find()->where(['slug' => $slug, 'status' => 1])->one();

        /**
         * Возвращаем 404 ошибку, если товар не существует или неактивен
         */
        if (empty($product)) {
            throw new \yii\web\HttpException('404','Товар не существует');
            return;
        }

        return $this->render('index', [
            'product' => $product
        ]);

    }
}
