<?php

namespace backend\controllers;

use Yii;
use common\models\Product;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ProductValue;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        $model->category = $this->getCategory($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', compact(['model', 'categories']));
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->category = $this->getCategory($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // Получение категории для форм создания и обновления товара
    protected function getCategory($model) {
        if (Yii::$app->request->post('category')) {
            return Yii::$app->request->post('category');
        } else {
            return $model->getMainCategoryId();
        }
    }

    /**
     * Добавление нового значения опции для товара
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAddProductValue()
    {
        // Получаем параметры из запроса
        $get = Yii::$app->request->get();
        $product_id = $get['product_id'];
        $option_id = $get['option_id'];
        $value_id = $get['value_id'];

        // Добавляем новую запись в таблицу опций товаров
        $newValue = new ProductValue();
        $newValue->product_id = $product_id;
        $newValue->option_id = $option_id;
        $newValue->value_id = $value_id;
        $newValue->save();

        $model = $this->findModel($product_id);

        // Получаем список значений опций товара
        $values = $model->getValues();

        // Формируем данные для построения GridView
        $dataProvider = new ActiveDataProvider([
            'query' => $values,
        ]);

        // Генерируем страницу с товаром
        return $this->render('update', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Удаление опции товара
     */
    public function actionDeleteProductValue()
    {
        // Получаем значения переданных параметров
        $get = Yii::$app->request->get();

        $product_id = $get['product_id'];
        $option_id = $get['option_id'];
        $value_id = $get['value_id'];

        // Находим опцию товара и удаляем её
        ProductValue::deleteAll(
            [
                'product_id' => $product_id,
                'option_id' => $option_id,
                'value_id' => $value_id,
            ]
        );

        $model = $this->findModel($product_id);

        // Получаем список значений опций товара
        $values = $model->getValues();

        // Формируем данные для построения GridView
        $dataProvider = new ActiveDataProvider([
            'query' => $values,
        ]);

        // Генерируем страницу с товаром
        return $this->render('update', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);

    }
}
