<?php

/**
 * @property string $shortTypeName
 * @property MenuItemHelper $owner
 */
abstract class AbstractMenuItemType extends CComponent
{
    /**
     * @var MenuItemHelper|null
     */
    private $_owner;

    /**
     * @param MenuItemHelper $owner
     * @return AbstractMenuItemType
     */
    public function setOwner(MenuItemHelper $owner)
    {
        $this->_owner = $owner;
        return $this;
    }

    /**
     * @return MenuItemHelper
     */
    public function getOwner()
    {
        return $this->_owner ?: Yii::app()->menuItemHelper;
    }

    public function getShortTypeName()
    {
        $classname = get_class($this);
        if (($pos = strpos($classname, 'MenuItemType')) === false) {
            throw new CException('You must set class name with MenuItemType suffix or override getShortTypeName method.');
        }
        return lcfirst(substr($classname, 0, $pos));
    }

    /**
     * @return string
     */
    abstract public function __toString();

    /**
     * @return string
     */
    abstract public function renderFormElement(MenuItem $item);

    /**
     * @param MenuItem $item
     * @param bool $asRouteIfPossible
     * @return string
     */
    abstract public function getUrl(MenuItem $item, $asRouteIfPossible = false);

    public function init()
    {
        // nothing to do
    }

    /**
     * @param MenuItem $item
     * @return boolean
     */
    abstract public function validate(MenuItem $item);

    /**
     * @param MenuItem $item
     * @return array
     */
    public function getMenuWidgetConfig(MenuItem $item)
    {
        $result = array(
            'label'         =>  $item->item_name,
            'url'           =>  $this->getUrl($item, true),
        );

        if (MenuItem::model()->getHtmlOptions($item->link_options)) {
            $result['linkOptions'] = MenuItem::model()->getHtmlOptions($item->link_options);
        }
        if (MenuItem::model()->getHtmlOptions($item->item_options)) {
            $result['itemOptions'] = MenuItem::model()->getHtmlOptions($item->item_options);
        }

        if ($item->items) {
            $owner = $this->owner;
            $result['items'] = array_map(function (MenuItem $i) use($owner) {
                return $owner->types[$i->item_type]->getMenuWidgetConfig($i);
            }, $item->items);
        }

        return $result;
    }
}