<?php

use yii\helpers\Url;

// Получаем текущего пользователя
$user = \Yii::$app->user->identity;

?>

<header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs">
                            <?=$user->username?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <p>
                                <?=$user->username?>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?=Url::to(['/site/logout'])?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="active">
                <a href="/"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-th"></i> <span>Products</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?=Url::to(['/product']);?>">Products</a>
                    </li>
                    <li>
                        <a href="<?=Url::to(['/category']);?>">Categories</a>
                    </li>
                    <li>
                        <a href="<?=Url::to(['/product-option']);?>">Options</a>
                    </li>
                    <li>
                        <a href="<?=Url::to(['/product-brand']);?>">Products Brands</a>
                    </li>
                    <li>
                        <a href="<?=Url::to(['/product-type']);?>">Products Types</a>
                    </li>
                    <li>
                        <a href="<?=Url::to(['/product-discount']);?>">Products Discounts</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="<?=Url::to(['/brand'])?>"><i class="fa fa-th"></i> <span>Brands</span></a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>