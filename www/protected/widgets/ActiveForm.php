<?php

Yii::import('bootstrap.widgets.TbActiveForm', true);
Yii::import('bootstrap.helpers.TbHtml');

class ActiveForm extends TbActiveForm
{
    /**
     * @param CModel $model
     * @param string $attribute
     * @param string $targetSelector in jQuery selector format
     * @param array $htmlOptions
     * @return string content
     */
    public function aliasField($model, $attribute, $targetSelector, $htmlOptions = array())
    {
        Yii::app()->clientScript->registerPackage('translit');
        $content = $this->textField($model, $attribute, $htmlOptions);
        $id = $this->getIdByModelAttribute($model, $attribute, $htmlOptions);
        return $content.TbHtml::script('
            $(function() {
                var $target = $('.CJavaScript::encode($targetSelector).');
                $target.liTranslit({
                    elAlias: $("#"+'.CJavaScript::encode($id).'),
                    status: false
                });
                $target.liTranslit("enable");
            });
        ');
    }

    /**
     * @param CModel $model The data model.
     * @param string $attribute The attribute.
     * @param string $targetSelector in jQuery selector format
     * @param array $htmlOptions Additional HTML attributes.
     * @param array $rowOptions Row attributes.
     * @return string The generated text field row.
     */
    public function aliasFieldRow($model, $attribute, $targetSelector, $htmlOptions = array(), $rowOptions = array())
    {
        $this->initRowOptions($rowOptions);

        $fieldData = array(array($this, 'aliasField'), array($model, $attribute, $targetSelector, $htmlOptions));

        return $this->customFieldRowInternal($fieldData, $model, $attribute, $rowOptions);
    }

    /**
     * @param CModel $model The data model.
     * @param string $attribute The attribute.
     * @param array $htmlOptions Additional HTML attributes.
     * @return string resolved id.
     */
    protected function getIdByModelAttribute($model, $attribute, $htmlOptions = array())
    {
        TbHtml::resolveNameID($model, $attribute, $htmlOptions);
        return $htmlOptions['id'];
    }
} 