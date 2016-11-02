<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use common\models\ProductOption;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#main">Main</a></li>

            <?php
                // Вкладок добавления изображений и выбора опций только для редактируемых товаров
                if (!$model->isNewRecord) { ?>

                <li><a data-toggle="tab" href="#images">Images</a></li>
                <li><a data-toggle="tab" href="#options">Options</a></li>

            <?php } ?>
        </ul>

        <div class="tab-content">
            <div id="main" class="tab-pane fade in active">
                <p>
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'preview_text')->textarea(['rows' => 6]) ?>
                    <?= $form->field($model, 'category')->dropDownList($model->getCategoriesDropDown(), $model->getCategoriesDropDownParams()) ?>
                    <?= $form->field($model, 'status')->checkbox(['label' => 'Active'])?>
                    <?= $form->field($model, 'full_description')->textarea(['rows' => 6]) ?>
                </p>
            </div>
            <div id="images" class="tab-pane fade">
                <p>
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
                </p>
            </div>
            <div id="options" class="tab-pane fade">
                <p>
                    <?php
                    // Вывод функционала добавление опций только для уже существующих товаров
                    if (!$model->isNewRecord) { ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                // Функционал добавления новых опций
                                // доступен только для уже созданных товаров

                                if (!$model->isNewRecord) {

                                    // Получаем список значений опций товара
                                    $values = $model->getValues();

                                    // Формируем данные для построения GridView
                                    $dataProvider = new ActiveDataProvider([
                                        'query' => $values,
                                    ]);

                                    // Получаем список доступных опций
                                    $options = ProductOption::find()->orderBy('sort')->asArray()->all();

                                    // Формируем массив для выпадающего списка выбора

                                    // Первый элемент массива должен быть пустым
                                    $selectItems = ['' => ''];

                                    $selectItems = ArrayHelper::merge($selectItems, ArrayHelper::map($options, 'id', 'name'));

                                    // Формируем форму для выбора опций для товара
                                    echo "<div class='row'>";
                                        echo "<div class='col-md-4 select-list'>";
                                            echo Html::dropDownList('option', '', $selectItems, ['class' => 'form-control product-form-options-option']);
                                        echo "</div>";
                                        echo "<div class='col-md-4 select-list'>";
                                            echo Html::dropDownList('value', '', [], ['class' => 'form-control product-form-options-value']);
                                        echo "</div>";
                                    echo "</div>";

                                    Pjax::begin(['enablePushState' => false]);

                                        echo Html::a('Add', '', ['class' => 'btn btn-primary btn-add-value']);

                                        // Выводим таблицу значений товара
                                        echo GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                'option.name',
                                                'value.name',
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
                                                    'urlCreator' => function ($action, $model, $key, $index) {
                                                        if ($action === 'delete') {
                                                            return Url::to([
                                                                '/product/delete-product-value',
                                                                'product_id' => $model->product_id,
                                                                'option_id' => $model->option_id,
                                                                'value_id' => $model->value_id,
                                                            ]);
                                                        }
                                                    }
                                                ]
                                            ],
                                        ]);

                                    Pjax::end();
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </p>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
</div>

<?php

    // Действие для получения значений опции
    $urlValues = Url::to(['/product-option/get-option-values']);

    // Действие добавления новой опции товару
    $urlNewOption = Url::to(['/product/add-product-value']);

    // Обработка события списка выбора опции
    $script = <<< JS
    // Событие выбора элемента списка опций
    $('.product-form-options-option').on('change', function() {        
        getOptionValues(); 
    })
    
    // Событие выбора элемента списка значений опций
    $('.product-form-options-value').on('change', function() {        
        // Обновляем ссылку для добавления значеня
        getNewValueUrl();
    })
        
    // Функция формирует адрес с параметрами для добавления товару опций
    function getNewValueUrl() {
      var baseUrl = '$urlNewOption';
      
      // Получаем id выбранной опции и id выбранного значения
      var option_id = $('.product-form-options-option').find(":selected").val();
      var value_id = $('.product-form-options-value').find(":selected").val();
      
      // Формируем адрес с параметрами
      var addValueUrl = baseUrl + '?product_id=$model->id&option_id=' + option_id + '&value_id=' + value_id;
      
      // Записываем адрес в ссылку
      $('.btn-add-value').attr('href', addValueUrl);                        
    }
    
    // Функция подбора значений для опций
    function getOptionValues() {
      // Получаем список значений выбранной опции
        
        // Id текущей опции
        var option = $('.product-form-options-option').find(":selected").val();
        
        // Делаем ajax запрос
        $.ajax({
           type: "get",
           url: '$urlValues',
           data: "option_id=" + option,
           success: function(res){
             // Преобразуем json со списком опций в массив
             var values = JSON.parse(res);
             
             // Формируем html для выпадающего списка значений
             var html_value = '';
             
             // Перебор значений и заполение строки html
             
             // Первый элемент списка должен быть пустым
             html_value += "<option value=''></option>";
             
             $.each(values, function(i, j) {
                html_value += "<option value='" + i + "'>" + j + "</option>";
             });
             
             // Запись получившихся значений в выпадающих список значений
             $('.product-form-options-value').html(html_value);
             
           }
        });
        
        // Обновляем ссылку для добавления значения
        getNewValueUrl();
    }
JS;
    //маркер конца строки, обязательно сразу, без пробелов и табуляции
    $this->registerJs($script, yii\web\View::POS_READY);
    $this->registerCss('
        .select-list {
            margin-bottom: 25px;
        }
        
        .btn-add-value {
            margin-bottom: 25px;
        }
    ');

?>
