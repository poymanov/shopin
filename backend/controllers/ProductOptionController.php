<?php

namespace backend\controllers;

use Yii;
use common\models\ProductOption;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ProductOptionValue;
use yii\helpers\ArrayHelper;

/**
 * ProductOptionController implements the CRUD actions for ProductOption model.
 */
class ProductOptionController extends Controller
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
        ];
    }

    /**
     * Lists all ProductOption models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ProductOption::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductOption model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);

        // Получаем запрос для вывода значений опции
        $values = $model->getValues();

        $dataProvider = new ActiveDataProvider([
            'query' => $values,
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new ProductOption model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductOption();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProductOption model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing ProductOption model.
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
     * Finds the ProductOption model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductOption the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductOption::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Метод для добавления значений опций через pjax
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAddValue()
    {
        // Получаем данные опции для записи значения
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $value = $post['value'];

        // Записываем значение в таблицу
        $newValue = new ProductOptionValue();
        $newValue->name = $value;
        $newValue->option_id = $id;
        $newValue->save();

        // Получаем модель опции
        $model = $this->findModel($id);

        // Получение значений опции
        $values = $model->getValues();

        return $this->render('update', ['model' => $model, 'values' => $values]);
    }

    /**
     * Метод для удаления значений опций через pjax
     */
    public function actionDeleteValue($id, $option_id)
    {
        // Удаляем значение опции
        ProductOptionValue::deleteAll(['id' => $id]);

        // Получаем модель опции
        $model = $this->findModel($option_id);

        // Получение значений опции
        $values = $model->getValues();

        // Возвращаем значения в представление
        return $this->render('update', ['model' => $model, 'values' => $values]);

    }

    public function actionGetOptionValues()
    {
        // Проверка: это только ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        // Получаем пришедные параметры
        $option_id = Yii::$app->request->get('option_id');

        $values = ProductOptionValue::find()->where(['option_id' => $option_id])->asArray()->all();

        // Преобразуем массив в более удобное представление
        $values = ArrayHelper::map($values, 'id', 'name');

        // Преобразуем массив в json и возвращаем обратно
        return json_encode($values);
    }
}
