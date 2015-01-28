<?php
/* @var $this ProductController */
/* @var $product Product */
$this->pageTitle = $product->seo_title;
$this->seoDescription = $product->seo_description;
$this->seoKeywords = $product->seo_keywords;
$this->breadcrumbs = array_merge(
    Yii::app()->breadcrumbsHelper->byDbUrlRule(array_reverse($product->category->allParents)),
    array($product->product_name)
);?>
<?php Yii::app()->clientScript->registerPackage('fancybox'); ?>

<div class="product-page">
    <h2 class="col-title"><?php echo CHtml::encode($product->product_name) ?><?php if($product->article) echo '/' . $product->article?></h2>
    <div class="clearfix">
        <div class="product-image">
            <a class="fancybox" href="<?php echo $this->fileHelper->productContext->getWebPath($product->file->path, 'origin') ?>">
                <img src="<?php echo $this->fileHelper->productContext->getWebPath($product->file->path, 'enough') ?>"/>
            </a>
        </div>
        <ul class="product-attribs">
        </ul>
        <div class="add-to-cart">
            <a class="product-like-private product-like" data-product-id="<?php echo $product->id; ?>" href="<?php echo Yii::app()->createUrl('product/product/add', array('product_id' => '__ID__')) ?>" style="text-decoration: none;"><span class="button-hochy">Это интересно!</span></a>
        </div>
        <?php if($product->price):?>
        <div class="price">
            цена <span><?php echo CHtml::encode($product->price)?></span> 
            п/м <?php echo Setting::byFixedName('price')->value ?>
        </div>
        <?php endif;?>
    </div>
    <div id="product-tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
            <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
                <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="product-fullinfo" aria-labelledby="ui-id-1" aria-selected="true"><a href="#product-fullinfo" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">Характеристики</a></li>                                                         
            </ul>
            <div class="tab-content clearfix ui-tabs-panel ui-widget-content ui-corner-bottom" id="product-fullinfo" aria-labelledby="ui-id-1" role="tabpanel" aria-expanded="true" aria-hidden="false">
                <p><?php echo $product->content?></p>
            </div>
    </div>
    <div class="gallery">
        <?php foreach ($product->packFile->files as $photo): ?>
            <a class="fancybox" rel="gallery" href="<?php echo $photo->path ?>">
                <?php echo CHtml::image($photo->path, '', array('width' => '100px', 'height' => '100px')) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>