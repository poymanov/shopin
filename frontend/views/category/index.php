<?php

use common\widgets\Categories\Categories;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = $category->name;

?>

<!--content-->
<div class="product">
    <div class="container">
        <div class="col-md-9">
            <div class="mid-popular">
                <?php if ($products) {?>
                    <?php foreach ($products as $product) {?>
                        <div class="col-md-4 item-grid1 catalog-item-grid simpleCart_shelfItem">
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
                                        <p ><label>$100.00</label><em class="item_price">$<?=$product->price?></em></p>
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
                    <?=LinkPager::widget(['pagination' => $pages])?>
                <?php } ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-3 product-bottom">
            <!--categories-->
            <div class=" rsidebar span_1_of_left">
                <?=Categories::widget()?>
            </div>

            <?php if ($discounts) {?>
                <section  class="sky-form">
                    <h4 class="cate">Discounts</h4>
                    <div class="row row-sky-form scroll-pane">
                        <div class="col col-4">
                            <?php foreach ($discounts as $discount) {?>
                                <label class="checkbox"><input type="checkbox" name="discounts"><i></i><?=$discount->name?></label>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            <?php } ?>

            <?php if ($types) {?>
                <section  class="sky-form">
                    <h4 class="cate">Type</h4>
                    <div class="row row-sky-form scroll-pane">
                        <div class="col col-4">
                            <?php foreach ($types as $type) {?>
                                <label class="checkbox"><input type="checkbox" name="types"><i></i><?=$type->name?></label>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            <?php } ?>

            <?php if ($brands) {?>
                <section  class="sky-form">
                    <h4 class="cate">Brand</h4>
                    <div class="row row-sky-form scroll-pane">
                        <div class="col col-4">
                            <?php foreach ($brands as $brand) {?>
                                <label class="checkbox"><input type="checkbox" name="brands"><i></i><?=$brand->name?></label>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            <?php } ?>
        </div>
        <div class="clearfix">
    </div>
</div>
<!--products-->