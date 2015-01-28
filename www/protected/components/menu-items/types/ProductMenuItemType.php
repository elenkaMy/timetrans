<?php

class ProductMenuItemType extends AbstractARMenuItemType
{
    public function __toString()
    {
        return Yii::t('admin', 'Product');
    }

    public function getRoute()
    {
        return 'product/product/index';
    }
}
