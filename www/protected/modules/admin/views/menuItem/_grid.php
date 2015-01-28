<?php
/* @var $this MenuItemController */
/* @var $dataProvider CActiveDataProvider */
/* @var $model MenuItem */
/* @var $menu Menu */
Yii::import('bootstrap.widgets.TbGridView');
?>
<div class="inner" id="menu-item-grid-inner">
    <?php $this->widget('TbGridView', array(
        'id'                =>  'menu-item-grid',
        'type'              =>  TbGridView::TYPE_STRIPED,
        'updateSelector'    =>  '#menu-item-grid .pagination a, #menu-item-grid .table thead th a',
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
                'name' => 'id',
                'value' => '$data->id',
                'htmlOptions' => array('class' => 'model_id'),
            ),
            array(
                'name' => 'parent_item_id',
                'value' => '$data->parentItem ? $data->parentItem->item_name : ""',
                'filter' => MenuItem::model()->higherItems($menu->id)->findAll(),
            ),
            array(
                'name'  =>  'item_name',
                'type'  =>  'raw',
                'value' =>  'is_null(Yii::app()->menuItemHelper->types[$data->item_type]->getUrl($data)) ? $data->item_name : CHtml::link(
                    CHtml::encode($data->item_name),
                    Yii::app()->menuItemHelper->types[$data->item_type]->getUrl($data),
                    array(
                        "target"    =>  "_blank",
                    )
                )',
            ),
            'position' => array(
                'name' => 'position',
                'value' => '"<input class=\"span11 change-position\" type=\"number\" value=\"$data->position\" /><img class=\"hide loader\"src=\"/img/admin/loader.gif\" /><div class=\"position-error hide\"></div>"',
                'type' => 'raw',
                'htmlOptions' => array(
                    'data-url' => Yii::app()->createUrl('admin/menuItem/changePosition', array('menu_item_id' => '__id__')),
                ),
            ),
            'created_at',
            /*
            'item_type',
            'menu_id',
            'id',
            'value',
            'updated_at',
            */
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{view}{update}{delete}',
                'buttons' => array(
                    'view'  => array(
                        'url'   =>  'Yii::app()->createUrl("admin/menuItem/view", array(MenuItemController::GET_PARAM_NAME => $data->id))',
                    ),
                    'update' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/menuItem/update", array(MenuItemController::GET_PARAM_NAME => $data->id))',
                    ),
                    'delete' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/menuItem/delete", array(MenuItemController::GET_PARAM_NAME => $data->id), "post")',
                    ),
                ),
            ),
        ),
    )); ?>
</div>