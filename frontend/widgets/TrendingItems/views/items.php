<?php

use yii\helpers\Url;

/* @var $product common\models\Product */

?>

<?php if ($products) {?>
    <div class="content-mid">
        <h3>Trending Items</h3>
        <label class="line"></label>
        <div class="mid-popular">
            <?php foreach ($products as $product) {?>
                <div class="col-md-3 item-grid1 trending-item-grid simpleCart_shelfItem">
                    <div class=" mid-pop">
                        <div class="pro-img">

                            <?php
                            $mainImage = $product->getMainImage();
                            ?>

                            <img src="<?=$mainImage?>" class="img-responsive" alt="">
                            <div class="zoom-icon ">
                                <a class="picture" href="<?=$mainImage?>" rel="title" class="b-link-stripe b-animate-go  thickbox"><i class="glyphicon glyphicon-search icon "></i></a>
                                <a href="<?=Url::to(['product/index', 'slug' => $product->slug])?>"><i class="glyphicon glyphicon-menu-right icon"></i></a>
                            </div>
                        </div>
                        <div class="mid-1">
                            <div class="women">
                                <div class="women-top">
                                    <span><?=$product->getMainCategoryName()?></span>
                                    <h6 class="item-header"><a href="single.html"><?=$product->name?></a></h6>
                                </div>
                                <div class="img item_add">
                                    <a href="#"><img src="/images/ca.png" alt=""></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="mid-2">
                                <p>
                                    <em class="item_price">
                                        $<?=Yii::$app->formatter->asDecimal($product->price, 2)?>
                                    </em>
                                </p>
                                <div class="block">
                                    <div class="starbox small ghosting"> </div>
                                </div>

                                <div class="clearfix"></div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
    </div>
<?php } ?>