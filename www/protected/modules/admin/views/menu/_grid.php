<?php
/* @var $this MenuController */
/* @var $dataProvider CActiveDataProvider */
/* @var $model Menu */
Yii::import('bootstrap.widgets.TbGridView');
?>
<div class="inner" id="menu-grid-inner">
    <?php $this->widget('TbGridView', array(
        'id'                =>  'menu-grid',
        'type'              =>  TbGridView::TYPE_STRIPED,
        'updateSelector'    =>  '#menu-grid .pagination a, #menu-grid .table thead th a',
        'afterAjaxUpdate'   =>  "js:function(id, data){var id = '#' + id + '-actions'; \$(id).replaceWith(\$(id, '<div>' + data + '</div>'))}",
        'selectableRows'    =>  0,
        'showTableOnEmpty'  =>  true,
        'dataProvider'      =>  $dataProvider,
        'filter'            =>  $model,
        'cssFile'           =>  false,
        'itemsCssClass'     =>  'table',
        'pagerCssClass'     =>  'pagination',
        'pager'             => array(
            'class'     => 'bootstrap.widgets.TbPager',
        ),
        'enablePagination'  => true,
        'template'          => '
            <div class="row-fluid">
                <div class="span8">{pager}</div>
                <div class="span4" style="margin-top:30px;">{summary}</div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    {items}
                </div>
            </div>
        ',
        'columns'           =>  array(
            array(
                'name' => 'label',
                'value' => '$data->label',
                'filter' => false,
            ),
            array(
                'class' =>  'bootstrap.widgets.TbButtonColumn',
                'template' => '{menu items}',
                'buttons' => array(
                    'menu items' => array(
                        'label' => Yii::t('admin', 'Menu Items'),
                        'url' => 'Yii::app()->createUrl("admin/menuItem/index", array("menu_id" => $data->id))',
                    ),
                ),
            ),
        ),
    )); ?>
</div>