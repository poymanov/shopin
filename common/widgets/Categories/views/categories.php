<?php

use yii\helpers\Url;

/* @var $category common\models\Category */

?>

<h4 class="cate">Categories</h4>

<?php if ($categories) {?>
    <ul class="menu-drop">
        <?php foreach ($categories as $category) {?>

            <?php

                // Дочернюю категорию выводить не нужно
                if (!empty($category->parent_id)) {
                    continue;
                }

            ?>

            <li class="item1">
                <a href="<?=Url::to(['category/index', 'slug' => $category->slug])?>">
                    <?=$category->name?>
                </a>

                <?php if ($category->childCategories) {?>
                    <ul class="cute">
                        <?php foreach ($category->childCategories as $childCategory) { ?>
                            <li class="subitem1">
                                <a href="<?=Url::to(['category/index', 'slug' => $childCategory->slug])?>">
                                    <?=$childCategory->name?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </li>
        <?php }?>
    </ul>
<?php } ?>