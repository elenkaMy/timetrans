<?php

class FileRequiredValidator extends CRequiredValidator
{
    /**
     * @param CModel $object
     * @param string $attribute
     * @throws CException if not found
     */
    protected function checkFileBehavior(CModel $object, $attribute)
    {
        Yii::import('application.components.db.behaviors.DbFileBehavior');

        $founded = false;
        foreach (array_keys($object->behaviors()) as $behaviorName) {
            if ($object->{$behaviorName} instanceof DbFileBehavior) {
                $founded = true;
                break;
            }
        }
        if (!$founded || !$object->hasFileColumn($attribute)) {
            throw new CException('Cannot find behavior for attribute '.$attribute);
        }
    }

    /**
     * @param CModel $object
     * @param string $attribute
     */
    protected function validateAttribute($object, $attribute)
    {
        switch (false) {
            case $this->requiredValue === null:
            case $this->strict === false:
            case $this->trim === true:
                throw new CException(__CLASS__.' does not allow requiredValue, strict & trim params.');
        }

        $this->checkFileBehavior($object, $attribute);
        $isEmpty = $object->isEmptyColumnSessionData($attribute);

        if ($isEmpty) {
            $message = $this->message !== null
                ? $this->message
                : Yii::t('yii', '{attribute} cannot be blank.');
            $this->addError($object, $attribute, $message);
        }
    }

    public function clientValidateAttribute($object, $attribute)
    {
    }
}
