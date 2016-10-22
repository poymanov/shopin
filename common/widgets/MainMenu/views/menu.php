<?php

use yii\helpers\Url;

?>

<nav class="navbar nav_bottom" role="navigation">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header nav_2">
        <button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
        <ul class="nav navbar-nav nav_1">
            <li><a class="color" href="<?=Url::home()?>">Home</a></li>

            <?php // Выводим список родительских категорий ?>

            <?php foreach ($categories as $category) {?>
                <li>
                    <a class="color" href="<?=Url::to(['category/index', 'slug' => $category->slug])?>"><?=$category->name?></a>
                </li>
            <?php } ?>

            <li ><a class="color6" href="contact.html">Contact</a></li>
        </ul>
    </div><!-- /.navbar-collapse -->

</nav>