<?php
/* @var $this ProductController */
/* @var $product Product */
?>

<div data-product-id="<?php echo $product->id; ?>" class="product">
    <a class="product-link" href="<?php echo Yii::app()->createUrl('product/product/index', array('record' => $product)) ?>"><?php echo CHtml::encode($product->product_name); ?></a>
    <a class="product-dislike" data-product-id="<?php echo $product->id; ?>"
       href="<?php echo Yii::app()->createUrl('product/product/delete', array('product_id' => '__ID__')) ?>"><span class="delete-button"><img src="/img/button_cancel_2665.png"></span></a>
</div>