<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ProductBrand */

$this->title = 'Create Product Brand';
$this->params['breadcrumbs'][] = ['label' => 'Product Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-brand-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
