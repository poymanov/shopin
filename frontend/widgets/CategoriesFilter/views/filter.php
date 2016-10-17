<?php

use yii\helpers\Url;

?>

<form action="<?=Url::toRoute(['category/index', 'slug' => $category->slug])?>" method="get">
    <?php if ($discounts) {?>
        <section  class="sky-form">
            <h4 class="cate">Discounts</h4>
            <div class="row row-sky-form scroll-pane">
                <div class="col col-4">
                    <?php foreach ($discounts as $discount) {?>
                        <?php

                        // Если данный чекбокс указан в фильтрах по товару,
                        // выделяем его в списке

                        $checked = false;

                        if (in_array($discount->id, $discountsWhere)) {
                            $checked = true;
                        }

                        ?>

                        <label class="checkbox">
                            <input type="checkbox" <?= $checked ? 'checked' : ''?> name="discount-<?=$discount->id?>" value="<?=$discount->id?>">
                            <i></i>
                            <?=$discount->name?>
                        </label>
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
                        <?php

                        // Если данный чекбокс указан в фильтрах по товару,
                        // выделяем его в списке

                        $checked = false;

                        if (in_array($type->id, $typesWhere)) {
                            $checked = true;
                        }

                        ?>
                        <label class="checkbox">
                            <input type="checkbox" <?= $checked ? 'checked' : ''?> name="types-<?=$type->id?>" value="<?=$type->id?>">
                            <i></i><?=$type->name?>
                        </label>
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

                        <?php

                        // Если данный чекбокс указан в фильтрах по товару,
                        // выделяем его в списке

                        $checked = false;

                        if (in_array($brand->id, $brandsWhere)) {
                            $checked = true;
                        }

                        ?>

                        <label class="checkbox">
                            <input type="checkbox" <?= $checked ? 'checked' : ''?> name="brands-<?=$brand->id?>" value="<?=$brand->id?>">
                            <i></i>
                            <?=$brand->name?>
                        </label>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } ?>

    <input type="submit" value="Search">

</form>