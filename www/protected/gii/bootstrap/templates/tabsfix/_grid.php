<?php
/**
 * The following variables are available in this template:
 */
/* @var $this BootstrapCode */
?>
<?php $underscoredModelClass = str_replace('-', '_', $this->class2id($this->getModelClass()));?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $dataProvider CActiveDataProvider */
/* @var $model <?php echo $this->modelClass ?> */
Yii::import('bootstrap.widgets.TbGridView');
?>
<div class="inner" id="<?php echo $this->class2id($this->modelClass) ?>-grid-inner">
    <?php echo "<?php" ?> $this->widget('TbGridView', array(
        'id'                =>  '<?php echo $this->class2id($this->modelClass) ?>-grid',
        'type'              =>  TbGridView::TYPE_STRIPED,
        'updateSelector'    =>  '#<?php echo $this->class2id($this->modelClass) ?>-grid .pagination a, #<?php echo $this->class2id($this->modelClass) ?>-grid .table thead th a',
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
<?php $count = 0; ?>
<?php foreach ($this->tableSchema->columns as $column){
    if (++$count == 7) {
        echo "            /*\n";
    }
    echo "            '{$column->name}',\n";
}
if ($count >= 7) {
    echo "            */\n";
}
?>      /*
        'position' => array(
            'name' => 'position',
            'value' => '"<input class=\"span11 change-position\" type=\"number\" value=\"$data->position\" /><img class=\"hide loader\"src=\"/images/admin/loader.gif\" /><div class=\"position-error hide\"></div>"',
            'type' => 'raw',
            'htmlOptions' => array(
                'data-url' => $this->createUrl('changePosition', array(<?php echo $underscoredModelClass ?>_id => '__id__')),
            ),
        ),
        */
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{view}{update}{delete}/*{frontend}*/',
                'buttons' => array(
                    'view'  => array(
                        'url'   =>  'Yii::app()->createUrl("<?php echo $this->module->id.'/'.$this->controllerId ?>/view", array(<?php echo $this->controllerClass ?>::GET_PARAM_NAME => $data->id))',
                    ),
                    'update' => array(
                        'url'   =>  'Yii::app()->createUrl("<?php echo $this->module->id.'/'.$this->controllerId ?>/update", array(<?php echo $this->controllerClass ?>::GET_PARAM_NAME => $data->id))',
                    ),
                    'delete' => array(
                        'url'   =>  'Yii::app()->createUrl("<?php echo $this->module->id.'/'.$this->controllerId ?>/delete", array(<?php echo $this->controllerClass ?>::GET_PARAM_NAME => $data->id), "post")',
                    ),
                    /*
                    'frontend'  =>  array(
                        'icon'  =>  'share-alt',
                        'label' =>  Yii::t('admin', 'Open on web site'),
                        'options'   =>  array(
                            'target'    =>  '_blank',
                        ),
                        'url'   =>  function (<?php echo $m = $this->modelClass?> $<?php echo lcfirst($m) ?>) {
                            return Yii::app()->createUrl('model route', array('record' => $<?php echo lcfirst($m) ?>));
                        },
                    ),
                    */
                ),
            ),
        ),
    )); ?>
</div>