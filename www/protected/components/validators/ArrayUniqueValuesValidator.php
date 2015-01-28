<?php
/**
 * Original sources and separateParams example:
 * @see http://www.yiiframework.com/extension/array-validator/
 * @author George Pligor <pligor@facebook.com> & Marco van 't Wout, Tremani 2012
 *
 * Example usage:
 *
 * public function rules() {
 *     return array(
 *         array('numberList', 'ArrayUniqueValuesValidator'),
 *     );
 * }
 */
class ArrayUniqueValuesValidator extends CValidator
{
    /**
     * @var integer sort flags for array_unique function
     */
    public $sortFlags = SORT_STRING;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute)
    {
        if ($object->hasErrors($attribute) || !is_array($object->$attribute)) {
            return;
        }

        switch (false) {
            case count($object->$attribute) === count(array_filter($object->$attribute, 'is_scalar')):
                $this->addError($object, $attribute, Yii::t('', 'Incorrect type of {attribute} elements'));
                break;
            case count($object->$attribute) === count(array_unique($object->$attribute, $this->sortFlags)):
                $this->addError($object, $attribute, Yii::t('', 'There are duplicates in {attribute}'));
                break;
            default:
                break;
        }
    }
}
