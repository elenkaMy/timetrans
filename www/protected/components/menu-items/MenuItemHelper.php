<?php

/**
 * Class MenuItemHelper
 *
 * @property AbstractMenuItemType[] $types
 */
class MenuItemHelper extends CApplicationComponent
{
    private $_types = array();

    /**
     * @param array $types
     */
    public function setTypes(array $types)
    {
        $this->_types = array();
        foreach($types as $config){
            if (!is_object($config)) {
                $config = Yii::createComponent($config);
            }
            if (!($config instanceof AbstractMenuItemType)) {
                throw new CException('MenuItemHelper types items must be an instance of AbstractMenuItemType.');
            }
            $config->owner = $this;
            $this->_types[$config->shortTypeName] = $config;
        }
    }

    /**
     * @return AbstractMenuItemType[]
     */
    public function getTypes()
    {
        return $this->_types;
    }

    public function init()
    {
        foreach ($this->types as $type)
        {
            $type->init();
        }
    }
}
