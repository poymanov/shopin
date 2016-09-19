<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Brand;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Brands';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Brand', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'image',
                'format' => 'html',
                'value' => function($item) {
                    return Html::img(Yii::$app->urlManagerFrontend->baseUrl . $item->image, ['class'=>'img-responsive']);
                }
            ],
            'name',
            [
                'label' => 'Link',
                'format' => 'html',
                'value' => function($item) {
                    return Html::a($item->href, $item->href, [
                        'target' => '_blank',
                    ]);
                }
            ],
            'sort',
            [
                'label' => 'Status',
                'value' => function($item) {
                    return $item->status ? Brand::STATUS_ACTIVE : Brand::STATUS_DISABLED;
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
