<?php

class SendForm extends CFormModel
{
    public $telephone;
     
    public function rules()
    {
        return array(
            array('telephone', 'required', 'on' => 'send'),
            array('telephone', 'length', 'max' => 255, 'on' => 'send'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array_map(function ($label) {
            return Yii::t('core', $label);
        }, array(
            'telephone'     =>  'Telephone',
        ));
    }     
}
