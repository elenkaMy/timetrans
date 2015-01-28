<?php
/* @var $this SettingController */
/* @var $dataProvider CActiveDataProvider */
/* @var $model Setting */
Yii::import('bootstrap.widgets.TbGridView');
?>
<div class="inner" id="setting-grid-inner">
    <?php $this->widget('TbGridView', array(
        'id'                =>  'setting-grid',
        'type'              =>  TbGridView::TYPE_STRIPED,
        'updateSelector'    =>  '#setting-grid .pagination a, #setting-grid .table thead th a',
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
            'label',
            array(
                'name'  =>  'setting_type',
                'value' =>  'Yii::t("setting", $data->setting_type)',
            ),
            array(
                'name'      =>  'value',
                'type'      =>  'raw',
                'value'     =>  '$this->grid->render("admin.views.setting.grid.$data->setting_type", array(
                    "model"     =>  $data,
                    "row"       =>  $row,
                ))',
                'sortable'  =>  false,
                'filter'    =>  false,
            ),
            'updated_at',
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{view}{update}',
                'buttons' => array(
                    'view'  => array(
                        'url'   =>  'Yii::app()->createUrl("admin/setting/view", array(SettingController::GET_PARAM_NAME => $data->id))',
                    ),
                    'update' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/setting/update", array(SettingController::GET_PARAM_NAME => $data->id))',
                    ),
                ),
            ),
        ),
    )); ?>
</div>