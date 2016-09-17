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

        $product = Product::find()->where(['slug' => $slug])->one();

        if (empty($product)) {
            throw new \yii\web\HttpException('404','Товар не существует');
            return;
        }

        return $this->render('index', [
            'product' => $product
        ]);

    }
}
