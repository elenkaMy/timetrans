<?php

class PageMenuItemType extends AbstractARMenuItemType
{
    public function __toString()
    {
        return Yii::t('admin', 'Static Page');
    }

    public function getRoute()
    {
        return 'page/page/index';
    }
}
