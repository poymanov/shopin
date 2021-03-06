<?php

use yii\helpers\Html;
use common\widgets\Categories\Categories;

$this->title = $product->name;

?>


<div class="single">

    <div class="container">
        <div class="col-md-9">
            <div class="col-md-5 grid">
                <div class="flexslider">
                    <ul class="slides">


                        <?php if ($product->productImages) {?>

                            <?php foreach ($product->productImages as $image) {?>
                                <li data-thumb="<?=$image->path ?>">
                                    <div class="thumb-image">
                                        <img src="<?=$image->path ?>" data-imagezoom="true" class="img-responsive">
                                    </div>
                                </li>
                            <?php } ?>

                        <?php } else { ?>

                            <li data-thumb="<?=$product->noImageProduct ?>">
                                <div class="thumb-image">
                                    <img src="<?=$product->noImageProduct ?>" class="img-responsive">
                                </div>
                            </li>

                        <?php } ?>

                    </ul>
                </div>
            </div>
            <div class="col-md-7 single-top-in">
                <div class="span_2_of_a1 simpleCart_shelfItem">
                    <h3><?=$this->title?></h3>
                    <p class="in-para">
                        <?=$product->getMainCategoryName()?>
                    </p>
                    <div class="price_single">
                        <span class="reducedfrom item_price">$ <?=$product->price?></span>
                        <a href="#">click for offer</a>
                        <div class="clearfix"></div>
                    </div>
                    <h4 class="quick">Quick Overview:</h4>
                    <p class="quick_desc">
                        <?=nl2br(Html::encode($product->preview_text))?>
                    </p>
                    <div class="wish-list">
                        <ul>
                            <li class="wish"><a href="#"><span class="glyphicon glyphicon-check" aria-hidden="true"></span>Add to Wishlist</a></li>
                            <li class="compare"><a href="#"><span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span>Add to Compare</a></li>
                        </ul>
                    </div>
                    <div class="quantity">
                        <div class="quantity-select">
                            <div class="entry value-minus">&nbsp;</div>
                            <div class="entry value"><span>1</span></div>
                            <div class="entry value-plus active">&nbsp;</div>
                        </div>
                    </div>
                    <a href="#" class="add-to item_add hvr-skew-backward">Add to cart</a>
                    <div class="clearfix"> </div>
                </div>

            </div>
            <div class="clearfix"> </div>
            <!---->
            <div class="tab-head">
                <nav class="nav-sidebar">
                    <ul class="nav tabs">
                        <li class="active"><a href="#tab1" data-toggle="tab">Product Description</a></li>
                        <li class=""><a href="#tab3" data-toggle="tab">Reviews</a></li>
                    </ul>
                </nav>
                <div class="tab-content one">
                    <div class="tab-pane active text-style" id="tab1">
                        <div class="facts">
                            <p>
                                <?=nl2br(Html::encode($product->full_description))?>
                            </p>
                        </div>
                    </div>
                    <div class="tab-pane text-style" id="tab3">

                        <div class="facts">
                            <p > There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined </p>
                            <ul>
                                <li><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Research</li>
                                <li><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Design and Development</li>
                                <li><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Porting and Optimization</li>
                                <li><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>System integration</li>
                                <li><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Verification, Validation and Testing</li>
                                <li><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Maintenance and Support</li>
                            </ul>
                        </div>

                    </div>

                </div>
                <div class="clearfix"></div>
            </div>
            <!---->
        </div>
        <!----->

        <div class="col-md-3 product-bottom product-at">
            <!--categories-->
            <div class=" rsidebar span_1_of_left">
                <?=Categories::widget()?>
            </div>

            <?php

            /**
             * Получение свойств товара
             */

            $discount = $product->discount;
            $type = $product->type;
            $brand = $product->brand;

            ?>

            <?php if ($discount) {?>
                <section  class="sky-form">
                    <h4 class="cate">Discounts</h4>
                    <div class="row row-sky-form scroll-pane">
                        <div class="col col-4">
                            <label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i><?=$discount->name?></label>
                        </div>
                    </div>
                </section>
            <?php } ?>

            <?php if ($type) {?>
                <section  class="sky-form">
                    <h4 class="cate">Type</h4>
                    <div class="row row-sky-form scroll-pane">
                        <div class="col col-4">
                            <label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i><?=$type->name?></label>
                        </div>
                    </div>
                </section>
            <?php } ?>

            <?php if ($brand) {?>
                <section  class="sky-form">
                    <h4 class="cate">Brand</h4>
                    <div class="row row-sky-form scroll-pane">
                        <div class="col col-4">
                            <label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i><?=$brand->name?></label>
                        </div>
                    </div>
                </section>
            <?php } ?>

        </div>
        <div class="clearfix"> </div>
    </div>
</div>
</div>

</div>
