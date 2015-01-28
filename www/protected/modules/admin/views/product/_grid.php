<?php
/* @var $this AdminProductController */
/* @var $dataProvider CActiveDataProvider */
/* @var $model Product */
Yii::import('bootstrap.widgets.TbGridView');
?>
<div class="inner" id="product-grid-inner">
    <?php $this->widget('TbGridView', array(
        'id'                =>  'product-grid',
        'type'              =>  TbGridView::TYPE_STRIPED,
        'updateSelector'    =>  '#product-grid .pagination a, #product-grid .table thead th a',
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
            array(
                'name' => 'category_id',
                'value' => '$data->category ? $data->category->category_name : ""',
                'filter' => ProductCategory::model()->getAllCategories(),
            ),
            'product_name',
            'article',
            'price',
            'position' => array(
                'name' => 'position',
                'value' => '"<input class=\"span11 change-position\" type=\"number\" value=\"$data->position\" /><img class=\"hide loader\"src=\"/img/admin/loader.gif\" /><div class=\"position-error hide\"></div>"',
                'type' => 'raw',
                'htmlOptions' => array(
                    'data-url' => $this->createUrl('changePosition', array('product_id' => '__id__')),
                ),
            ),
            'visible' => array(
                'name' => 'visible',
                'value' => '$data->visible ? "Да" : "Нет"',
            ),
            'created_at',
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{view}{update}{delete}{frontend}',
                'buttons' => array(
                    'view'  => array(
                        'url'   =>  'Yii::app()->createUrl("admin/adminProduct/view", array(AdminProductController::GET_PARAM_NAME => $data->id))',
                    ),
                    'update' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/adminProduct/update", array(AdminProductController::GET_PARAM_NAME => $data->id))',
                    ),
                    'delete' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/adminProduct/delete", array(AdminProductController::GET_PARAM_NAME => $data->id), "post")',
                    ),
                    'frontend'  =>  array(
                        'icon'  =>  'share-alt',
                        'label' =>  Yii::t('admin', 'Open on web site'),
                        'options'   =>  array(
                            'target'    =>  '_blank',
                        ),
                        'url'   =>  function (Product $data) {
                            return Yii::app()->createUrl('product/product/index', array('record' => $data));
                        },
                    ),
                ),
            ),
        ),
    )); ?>
</div>