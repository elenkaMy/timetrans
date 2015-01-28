<?php
/* @var $this AdminProductCategoryController */
/* @var $dataProvider CActiveDataProvider */
/* @var $model ProductCategory */
Yii::import('bootstrap.widgets.TbGridView');
?>
<div class="inner" id="product-category-grid-inner">
    <?php $this->widget('TbGridView', array(
        'id'                =>  'product-category-grid',
        'type'              =>  TbGridView::TYPE_STRIPED,
        'updateSelector'    =>  '#product-category-grid .pagination a, #product-category-grid .table thead th a',
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
//            array(
//                'name' => 'parent_category_id',
//                'value' => '$data->parentCategory ? $data->parentCategory->category_name : ""',
//                'filter' => ProductCategory::model()->higherCategories()->findAll(),
//            ),
            'category_name',
            'position' => array(
                'name' => 'position',
                'value' => '"<input class=\"span11 change-position\" type=\"number\" value=\"$data->position\" /><img class=\"hide loader\"src=\"/img/admin/loader.gif\" /><div class=\"position-error hide\"></div>"',
                'type' => 'raw',
                'htmlOptions' => array(
                    'data-url' => $this->createUrl('changePosition', array('product_category_id' => '__id__')),
                ),
            ),
            'created_at',
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{view}{update}{delete}{products}{frontend}',
                'buttons' => array(
                    'view'  => array(
                        'url'   =>  'Yii::app()->createUrl("admin/adminProductCategory/view", array(AdminProductCategoryController::GET_PARAM_NAME => $data->id))',
                    ),
                    'update' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/adminProductCategory/update", array(AdminProductCategoryController::GET_PARAM_NAME => $data->id))',
                    ),
                    'delete' => array(
                        'url'   =>  'Yii::app()->createUrl("admin/adminProductCategory/delete", array(AdminProductCategoryController::GET_PARAM_NAME => $data->id), "post")',
                        'visible' => '$data->fixed_name === null',
                    ),
                    'frontend'  =>  array(
                        'icon'  =>  'share-alt',
                        'label' =>  Yii::t('admin', 'Open on web site'),
                        'options'   =>  array(
                            'target'    =>  '_blank',
                        ),
                        'url'   =>  function (ProductCategory $data) {
                            return Yii::app()->createUrl('product/category/index', array('record' => $data));
                        },
                    ),
                    'products'  =>  array(
                        'icon'  =>  'list',
                        'label' =>  Yii::t('admin', 'Products'),
                        'url'   =>  function (ProductCategory $data) {
                            return Yii::app()->createUrl('admin/adminProduct/index', array(
                                'Product'   =>  array(
                                    'category_id'   =>  $data->id,
                                ),
                            ));
                        },
                    ),
                ),
            ),
        ),
    )); ?>
</div>