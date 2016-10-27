<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\ProductOption */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>



    <?php

    // Если это не новая запись,
    // то возможно добавлять для данной опции значения и смотреть уже существующие
    if (!$model->isNewRecord) {

        // Получаем значения опции
        $values = $model->getValues();

        echo Html::tag('h3', 'Option values');

        Pjax::begin(['enablePushState' => false]);

            // Формируем данные для построения GridView
            $dataProvider = new ActiveDataProvider([
                'query' => $values,
            ]);

            // Id опции для работы с GridView
            $option_id = $model->id;

            // Выводим форму добавления нового значения опции
            echo Html::beginForm(['add-value'], 'post', ['data-pjax' => '', 'class' => 'form-inline']);
            echo Html::input('text', 'value', '', ['class' => 'form-control']);
            echo Html::input('hidden', 'id', $option_id , ['class' => 'form-control']);
            echo Html::submitButton('Add value', ['class' => 'btn btn-primary']);
            echo Html::endForm();

            // Выводим таблицу значений опции
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return false;
                            },
                            'view' => function ($url, $model, $key) {
                                return false;
                            },
                        ],
                        'urlCreator' => function ($action, $model, $key, $index) use ($option_id) {
                            if ($action === 'delete') {
                                return Url::to(['delete-value', 'id' => $model->id, 'option_id' => $option_id]);
                            }
                        }
                    ]
                ],
            ]);
        Pjax::end();

    }

    ?>

</div>
