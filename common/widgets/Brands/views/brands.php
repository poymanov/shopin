<div class="container">
    <div class="brand">
        <?php foreach ($brands as $brand) {?>
            <div class="col-md-3 brand-grid">
                <a href="<?=$brand->href?>" target="_blank">
                    <img src="<?=$brand->image?>" class="img-responsive" alt="<?=$brand->alt?>" title="<?=$brand->title?>">
                </a>
            </div>
        <?php } ?>
        <div class="clearfix"></div>
    </div>
</div>
