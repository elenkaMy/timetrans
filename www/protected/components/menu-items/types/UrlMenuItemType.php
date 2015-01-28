<?php

class UrlMenuItemType extends AbstractMenuItemType
{
    public function __toString()
    {
        return Yii::t('admin', 'Url');
    }

    public function renderFormElement(MenuItem $item)
    {
        $widget = new CWidget();
        return $widget->widget('application.components.menu-items.widgets.UrlMenuItemTypeWidget', array(
            'menuItem'      =>  $item,
            'menuItemType'  =>  $this,
        ), true);
    }

    public function getUrl(MenuItem $item, $asRouteIfPossible = false)
    {
        return (string) $item->value;
    }

    public function init()
    {
        return parent::init();
    }

    public function validate(MenuItem $item)
    {
        $validator = new CStringValidator();
        $validator->min = 1;
        $validator->allowEmpty = false;
        $validator->max = 255;
        $validator->attributes = array('value');
        $validator->validate($item);
        return $item->hasErrors('value');
    }
}