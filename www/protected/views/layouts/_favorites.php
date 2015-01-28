<?php
/* @var $this Controller */
?>
<?php /* @var Favourites $favourites */ $favourites = Yii::app()->favourites;?>
<div class="col-title">
    <?php echo 'Хочу:'; ?>
</div>
<div class="chochy-products">
    <?php foreach($favourites->getFavouritesProducts() as $product):?>
        <div data-product-id="<?php echo $product->id; ?>" class="product">
            <a class="product-link" href="<?php echo Yii::app()->createUrl('product/product/index', array('record' => $product)) ?>"><?php echo CHtml::encode($product->product_name); ?></a>
            <a class="product-dislike" data-product-id="<?php echo $product->id; ?>" href="<?php echo Yii::app()->createUrl('product/product/delete', array('product_id' => '__ID__')) ?>"><span class="delete-button"><img src="/img/button_cancel_2665.png"></span></a>
        </div>
    <?php endforeach;?>
</div> 
<div class="send-modal-form"><div class="available"><!-- --></div></div>
<div class="call-button">
    <a class="sendButton send-button-form" href="#" data-url="<?php echo Yii::app()->createUrl('product/product/send');?>" name="sendButton" style="text-decoration: none;"><span class="send-button">Получить информацию</span></a>
</div>