<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;


/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="product-form">
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'images[]')->fileInput(['multiple' => true]) ?>

    <?php
        // Вывод загруженных изображений
        foreach ($model->productImages as $image) {

            if ($image->main) {
                $model->loadedImages = $image->id;
            }
            
            echo "<div>";
                echo $form->field($model, 'loadedImages')->radio(['value' => $image->id, 'uncheck' => null]);
                echo Html::img(Yii::$app->urlManagerFrontend->baseUrl . $image->path);
                echo $form->field($model, 'deleteImages[]')->checkbox(['value' => $image->id, 'uncheck' => null]);
            echo "</div>";
        }
    ?>

    <?= $form->field($model, 'preview_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'category')->dropDownList($model->getCategoriesDropDown(), $model->getCategoriesDropDownParams()) ?>

    <?= $form->field($model, 'full_description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
