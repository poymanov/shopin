<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>

<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

<?php echo \Yii::$app->view->renderFile('@frontend/views/common/header.php'); ?>

<?=$content ?>

<?php echo \Yii::$app->view->renderFile('@frontend/views/common/footer.php'); ?>

<?php $this->endBody() ?>

<!--//menu-->
<!----->
<!---//End-rate---->

</body>
</html>
<?php $this->endPage() ?>
