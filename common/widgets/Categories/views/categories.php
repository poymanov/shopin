<h4 class="cate">Categories</h4>

<?php if ($categories) {?>
    <ul class="menu-drop">
        <?php foreach ($categories as $category) {?>
            <li class="item1"><a href="#"><?=$category->name?></a>
<!--                <ul class="cute">-->
<!--                    <li class="subitem1"><a href="product.html">Cute Kittens </a></li>-->
<!--                    <li class="subitem2"><a href="product.html">Strange Stuff </a></li>-->
<!--                    <li class="subitem3"><a href="product.html">Automatic Fails </a></li>-->
<!--                </ul>-->
            </li>
        <?php }?>
    </ul>
<?php } ?>