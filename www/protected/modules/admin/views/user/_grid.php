<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */
/* @var $model User */
Yii::import('bootstrap.widgets.TbGridView');
?>
<div class="inner" id="user-grid-inner">
    <?php $this->widget('TbGridView', array(
        'id'                =>  'user-grid',
        'type'              =>  TbGridView::TYPE_STRIPED,
        'updateSelector'    =>  '#user-grid .pagination a, #user-grid .table thead th a',
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
            'id',
            'username',
            'email',
            'created_at',
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{view}{update}{delete}',
                'buttons' => array(
                    'view'  => array(
                        'url'   =>  'Yii::app()->createUrl("admin/user/view", array(UserController::GET_PARAM_NAME => $data->id))',
                    ),
                    'update' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/user/update", array(UserController::GET_PARAM_NAME => $data->id))',
                    ),
                    'delete' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/user/delete", array(UserController::GET_PARAM_NAME => $data->id), "post")',
                    ),
                ),
            ),
        ),
    )); ?>
</div>