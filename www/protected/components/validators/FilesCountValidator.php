<?php

class FilesCountValidator extends CValidator
{
    public $behaviorName = 'DbMultipleFileBehavior';

    /**
     * @var integer min files count
     */
    public $min = 1;

    /**
     * @var integer max files count
     */
    public $max;

    /**
     * @var integer exact count. Defaults to null, meaning no exact count limit.
     */
    public $is;

    /**
     * @var string user-defined error message used when the count is too short.
     */
    public $tooShort;

    /**
     * @var string user-defined error message used when the count is too long.
     */
    public $tooLong;
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
        $this->checkFileBehavior($object, $attribute);
        $count = $object->getSessionFilesCount($attribute);

        if (!is_null($this->min) && $count < $this->min) {
            $message = !is_null($this->tooShort)
                ? $this->tooShort
                : Yii::t('file','{attribute}: count of uploaded files is too small (minimum is {min} elements).');
            $this->addError($object, $attribute, $message, array('{min}' => $this->min));
        }
        if (!is_null($this->max) && $count > $this->max) {
            $message = !is_null($this->tooLong)
                ? $this->tooLong
                : Yii::t('file','{attribute}: count of uploaded files is too large (maximum is {max} elements).');
            $this->addError($object, $attribute, $message, array('{max}' => $this->max));
        }
        if(!is_null($this->is) && $count !== $this->is) {
            $message = !is_null($this->message)
                ? $this->message
                : Yii::t('file','{attribute}: count of uploaded files is wrong (should be {length} elements).');
            $this->addError($object, $attribute, $message, array('{length}' => $this->is));
        }
    }
}
