<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>

<div class="container">
    <div class="four">
        <h3><?= Html::encode($this->title) ?></h3>
        <p><?= nl2br(Html::encode($message)) ?></p>
        <a href="<?=Url::home()?>" class="hvr-skew-backward">Back To Home</a>
    </div>
</div>