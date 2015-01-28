<?php
/* @var $this AdminPageController */
/* @var $dataProvider CActiveDataProvider */
/* @var $model Page */
Yii::import('bootstrap.widgets.TbGridView');
?>
<div class="inner" id="page-grid-inner">
    <?php $this->widget('TbGridView', array(
        'id'                =>  'page-grid',
        'type'              =>  TbGridView::TYPE_STRIPED,
        'updateSelector'    =>  '#page-grid .pagination a, #page-grid .table thead th a',
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
                'name' => 'parent_page_id',
                'value' => '$data->parentPage ? $data->parentPage->page_name : ""',
                'filter' => Page::model()->higherPages()->findAll(),
            ),
            'page_name',
            array(
                'name'  =>  'alias',
                'type'  =>  'raw',
                'value' =>  'CHtml::link(
                    CHtml::encode($data->alias),
                    Yii::app()->createUrl("page/page/index", array(
                        "record" => $data,
                    )),
                    array(
                        "target" => "_blank",
                    )
                )',
            ),
            'position' => array(
                'name' => 'position',
                'value' => '"<input class=\"span11 change-position\" type=\"number\" value=\"$data->position\" /><img class=\"hide loader\"src=\"/img/admin/loader.gif\" /><div class=\"position-error hide\"></div>"',
                'type' => 'raw',
                'htmlOptions' => array(
                    'data-url' => $this->createUrl('changePosition', array('page_id' => '__id__')),
                ),
            ),
            'created_at',
            /*
            'id',
            'short_content',
            'seo_title',
            'seo_description',
            'seo_keywords',
            'content',
            'updated_at',
            'fixed_name',
            */
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{view}{update}{delete}{frontend}',
                'buttons' => array(
                    'view'  => array(
                        'url'   =>  'Yii::app()->createUrl("admin/adminPage/view", array(AdminPageController::GET_PARAM_NAME => $data->id))',
                    ),
                    'update' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/adminPage/update", array(AdminPageController::GET_PARAM_NAME => $data->id))',
                    ),
                    'delete' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/adminPage/delete", array(AdminPageController::GET_PARAM_NAME => $data->id), "post")',
                        'visible' => '$data->fixed_name === null',
                    ),
                    'frontend'  =>  array(
                        'icon'  =>  'share-alt',
                        'label' =>  Yii::t('admin', 'Open on web site'),
                        'options'   =>  array(
                            'target'    =>  '_blank',
                        ),
                        'url'   =>  function (Page $page) {
                            return Yii::app()->createUrl('page/page/index', array('record' => $page));
                        },
                    ),
                ),
            ),
        ),
    )); ?>
</div>