<?php
/* @var $favourites Favourites */
/* @var $this ProductController */
/* @var $date string */
/* @var $form SendForm */
?>

<?php $favourites = Yii::app()->favourites;?> 
<?php if ($form):?>
<p>
    <?php echo 'Телефон: '. CHtml::encode($form->telephone);?>
</p><br/>
<p>Список товаров:</p>
<?php foreach($favourites->getFavouritesProducts() as $product):?>
    <p>
        <a href="<?php echo Yii::app()->createAbsoluteUrl('product/product/index', array('record' => $product)) ?>"><?php echo CHtml::encode($product->product_name); ?></a>
    </p>
<?php endforeach;?>
<?php endif;?>

<p><?php echo 'Дата: '.$date ?></p>
