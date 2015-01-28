<?php

class TextMenuItemTypeWidget extends CWidget
{
    /**
     * @var MenuItem
     */
    public $menuItem;
    /**
     * @var AbstractMenuItemType
     */
    public $menuItemType;

    public function init()
    {
        if (empty($this->menuItem) || !($this->menuItem instanceof MenuItem)) {
            throw new CException('MenuItem property must be defined.');
        }
        if (empty($this->menuItemType) || !($this->menuItemType instanceof AbstractMenuItemType)) {
            throw new CException('MenuItemType property must be defined.');
        }
    }

    public function run()
    {
        $this->render('text', array(
            'menuItemType'  =>  $this->menuItemType,
        ));
    }
}