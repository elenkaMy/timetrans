<?php
/* @var $this CategoryController */
/* @var $category ProductCategory */
$this->pageTitle = $category->seo_title;
$this->seoDescription = $category->seo_description;
$this->seoKeywords = $category->seo_keywords;
//$this->breadcrumbs = array_merge(
//    Yii::app()->breadcrumbsHelper->byDbUrlRule(array_reverse($category->allParents)),
//    array($category->category_name)
//);
?><br>

<h1 class="col-title">
    <?php echo CHtml::encode($category->category_name)?> 
</h1>
<ul class="products-list clearfix">
    <?php if ($category->products) :?>
        <?php foreach($category->products as $product):?>
            <li>
                <a href="<?php echo Yii::app()->createUrl('product/product/index', array('record' => $product)) ?>">
                    <img src="<?php echo $this->fileHelper->productContext->getWebPath($product->file->path,'small') ?>" />
                </a>
                <div style="display: inline">
                    <?php 
                        echo CHtml::encode($product->product_name); 
                        if($product->article)
                            echo '/' . $product->article;
                    ?>
                </div>
                <?php if($product->price):?>
                    <div class="price">
                        Цена: <span class="num"><?php echo CHtml::encode($product->price) ?></span>
                        <span class="cur">(от) <?php echo Setting::byFixedName('price')->value ?></span>
                    </div>
                <?php endif;?>
                <div class="add-to-cart-main">
                    <a class="product-like" data-product-id="<?php echo $product->id; ?>" href="<?php echo Yii::app()->createUrl('product/product/add', array('product_id' => '__ID__')) ?>" style="text-decoration: none;"><span class="button-hochy">Это интересно!</span></a>
                </div>    
                <br>
            </li>
        <?php endforeach ?>
    <?php endif ?>
</ul>
<?php echo $category->content ?><br>