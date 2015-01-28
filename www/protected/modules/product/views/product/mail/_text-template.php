<?php
/* @var $favourites Favourites */
/* @var $this ProductController */
/* @var $date string */
/* @var $form SendForm */
?>
 
<?php $favourites = Yii::app()->favourites;?> 
<?php if ($form):?>
    <?php echo 'Телефон: '.$form->telephone;?>.
    Список товаров:
    <?php foreach($favourites->getFavouritesProducts() as $product):?>
        <?php echo CHtml::encode($product->product_name); ?>
        <?php echo Yii::app()->createAbsoluteUrl('product/product/index', array('record' => $product)) ?>.
    <?php endforeach;?>
<?php endif;?>

Дата: <?php echo $date; ?>
