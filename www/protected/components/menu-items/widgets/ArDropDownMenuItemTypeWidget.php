<?php

class ArDropDownMenuItemTypeWidget extends CWidget
{
    /**
     * @var MenuItem
     */
    public $menuItem;
    /**
     * @var AbstractARMenuItemType
     */
    public $menuItemType;

    public function init()
    {
        if (empty($this->menuItem) || !($this->menuItem instanceof MenuItem)) {
            throw new CException('MenuItem property must be defined.');
        }
        if (empty($this->menuItemType) || !($this->menuItemType instanceof AbstractARMenuItemType)) {
            throw new CException('MenuItemType property must be defined.');
        }
    }

    public function run()
    {
        $this->render('drop-down', array(
            'menuItem'      =>  $this->menuItem,
            'menuItemType'  =>  $this->menuItemType,
        ));
    }
}
