<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductBrand */

$this->title = 'Update Product Brand: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Product Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-brand-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
