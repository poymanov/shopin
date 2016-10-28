<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\ProductOption */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Product Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-option-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php

        // Вывод данных опции
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'sort',
            ],
        ]);

        echo Html::tag('h3', 'Option values');

        // Вывод значений опции
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'options' => ['class' => 'grid-view row col-md-4'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
            ],
        ]);

    ?>



</div>
