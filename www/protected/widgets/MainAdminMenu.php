<?php

Yii::import('bootstrap.widgets.TbMenu', true);

class MainAdminMenu extends TbMenu
{
    public $type = 'pills'; // '', 'tabs', 'pills' (or 'list')
    public $stacked = false; // whether this is a stacked menu
    public $activateParents = true;

    public function init()
    {
        $this->items = array(
            array('label' => Yii::t('admin', 'Home'), 'url' => array('/admin/default/index')),
            array('label' => Yii::t('admin', 'Pages'), 'url' => array('/admin/adminPage/index')),
            array('label' => Yii::t('admin', 'Shop'), 'items' => array(
                array('label' => Yii::t('admin', 'Products'), 'url' => array('/admin/adminProduct/index')),
                array('label' => Yii::t('admin', 'Product Categories'), 'url' => array('/admin/adminProductCategory/index')),
            )),
            array('label' => Yii::t('admin','Configuration'), 'url' => array(''), 'items' => array(
                array('label' => Yii::t('admin', 'Users'), 'url' => array('/admin/user/index')),
                array('label' => Yii::t('admin','Settings'), 'url' => array('/admin/setting/index')),
                array('label' => Yii::t('admin','Menu'), 'url' => array('/admin/menu/index')),
            )),
            array('label' => Yii::t('admin', 'Logout'), 'url'=> array('/admin/auth/logout')),
        );

        return parent::init();
    }
}
