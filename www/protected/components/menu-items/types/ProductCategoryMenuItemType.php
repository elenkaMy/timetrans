<?php

class ProductCategoryMenuItemType extends AbstractARMenuItemType
{
    public function __toString()
    {
        return Yii::t('admin', 'Product Category');
    }

    public function getRoute()
    {
        return 'product/category/index';
    }
}
