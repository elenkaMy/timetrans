<?php
/* @var $this PageController */
/* @var $page Page */
/* @var $products Product[] */
?>
<?php
$this->pageTitle = $page->seo_title;
$this->seoDescription = $page->seo_description;
$this->seoKeywords = $page->seo_keywords;
?>
<span class="col-title"><p>ОТ ПРОИЗВОДИТЕЛЯ<br>Художественная&nbsp; ковка</p></span>
<ul class="products-list clearfix">
<?php foreach($products as $productForMainPage):?>
    <li>
        <a href="<?php echo Yii::app()->createUrl('product/product/index', array('record' => $productForMainPage)) ?>">
        <img src="<?php echo $this->fileHelper->productContext->getWebPath($productForMainPage->file->path, 'small') ?>" />
        </a>
        <div style="display: inline">
            <?php echo CHtml::encode($productForMainPage->product_name) ?>
        </div>
        <?php if ($productForMainPage->price):?>
            <div class="price">
                Цена: <span class="num">
                    <?php echo CHtml::encode($productForMainPage->price) ?>
                </span>
                <span class="cur"><?php echo Setting::byFixedName('price')->value ?></span>
            </div>
        <?php endif;?>
        <div class="add-to-cart-main">
            <a class="product-like" data-product-id="<?php echo $productForMainPage->id; ?>" href="<?php echo Yii::app()->createUrl('product/product/add', array('product_id' => '__ID__')) ?>" style="text-decoration: none;"><span class="button-hochy">Это интересно!</span></a>
        </div>
    </li>
<?php endforeach;?>

<?php echo Setting::byFixedName('text_main')->value ?>
