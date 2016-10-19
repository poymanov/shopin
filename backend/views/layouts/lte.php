<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\LteAsset;
use yii\helpers\Html;
use backend\widgets\BreadcrumbsCustom;

LteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
    <div class="wrapper">

        <?php echo \Yii::$app->view->renderFile('@backend/views/common/header.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Dashboard
                    <small>Control panel</small>
                </h1>

                <?= BreadcrumbsCustom::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'tag' => 'ol',
                ]) ?>
                
            </section>

            <!-- Main content -->
            <section class="content">

                <?= $content ?>

            </section>
            <!-- /.content -->
        </div>

        <?php echo \Yii::$app->view->renderFile('@backend/views/common/footer.php'); ?>

    </div>
<?php $this->endBody() ?>
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
</body>
</html>
<?php $this->endPage() ?>
