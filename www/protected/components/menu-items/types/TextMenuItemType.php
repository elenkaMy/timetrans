<?php

class TextMenuItemType extends AbstractMenuItemType
{
    public function __toString()
    {
        return Yii::t('admin', 'Text');
    }

    public function renderFormElement(MenuItem $item)
    {
        $widget = new CWidget();
        return $widget->widget('application.components.menu-items.widgets.TextMenuItemTypeWidget', array(
            'menuItem'      =>  $item,
            'menuItemType'  =>  $this,
        ), true);
    }

    public function getUrl(MenuItem $item, $asRouteIfPossible = false)
    {
        return null;
    }

    public function validate(MenuItem $item)
    {
        $item->value = '-';
        return true;
    }
}