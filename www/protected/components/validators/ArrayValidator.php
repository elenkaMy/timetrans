<?php
/**
 * Example usage:
 *
 * public function rules() {
 *     return array(
 *         array('numberList', 'ArrayValidator', 'validator'=>'numerical', 'params'=>array(
 *             'integerOnly'=>true, 'allowEmpty'=>false
 *         )),
 *     );
 * }
 */
class ArrayValidator extends CValidator
{
    /**
     * @var integer maximum count. Defaults to null, meaning no maximum limit.
     */
    public $max;

    /**
     * @var integer minimum count. Defaults to null, meaning no minimum limit.
     */
    public $min;

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
     * @var string|array name of the validator class (example: 'numerical' or 'CustomValidator').
     * Defaults to null, meaning validate only count of elements
     * You can use array with key => value pairs
     */
    public $validator;

    /**
     * @var array parameters passed to the validator class
     */
    public $params = array();

    /**
     * @var bool use a separate params array depending on array attribute keys
     */
    public $separateParams = false;

    /**
     * @var boolean whether the attribute value can be null or empty. Defaults to true,
     * meaning that if the attribute is empty, it is considered valid.
     */
    public $allowEmpty = true;

    protected function validateArrayCount($object, $attribute)
    {
        $result = true;
        $count = count($object->$attribute);

        if (!is_null($this->min) && $count < $this->min) {
            $message = !is_null($this->tooShort)
                ? $this->tooShort
                : Yii::t('yii','{attribute} is too small (minimum is {min} elements).');
            $this->addError($object, $attribute, $message, array('{min}' => $this->min));
            $result = false;
        }
        if (!is_null($this->max) && $count > $this->max) {
            $message = !is_null($this->tooLong)
                ? $this->tooLong
                : Yii::t('yii','{attribute} is too big (maximum is {max} elements).');
            $this->addError($object, $attribute, $message, array('{max}' => $this->max));
            $result = false;
        }
        if(!is_null($this->is) && $count !== $this->is) {
            $message = !is_null($this->message)
                ? $this->message
                : Yii::t('yii','{attribute} is of the wrong count (should be {length} elements).');
            $this->addError($object, $attribute, $message, array('{length}' => $this->is));
            $result = false;
        }

        return $result;
    }

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute)
    {
        if ($this->isEmpty($object->$attribute)) {
            if ($this->allowEmpty === true) {
                $object->$attribute = null;
                return;
            }
            $this->addError($object, $attribute, Yii::t('', '{attribute} cannot be empty.'));
            return;
        }

        if (!is_array($object->$attribute) ) {
            $this->addError($object, $attribute, Yii::t('', 'You are trying to validate a non-array attribute.'));
            return;
        }

        if (!$this->validateArrayCount($object, $attribute)) {
            return;
        }

        if (is_null($this->validator)) {
            return;
        }

        // Loop validator for every array element
        $attributeArray = $this->prepareAttributeArray($object, $attribute);
        foreach($attributeArray as $key => &$value) { // by reference
            $object->$attribute = $value; // temporary store single value in object attribute

            if (is_array($this->validator) && !isset($this->validator[$key])) {
                continue;
            }
            $validatorObject = self::createValidator(
                is_array($this->validator) ? $this->validator[$key] : $this->validator,
                $object, array($attribute)
            );
            $this->setValidatorParams($validatorObject, $key);

            $validatorObject->validate($object);
            $value = $object->$attribute; // put validated value back in attribute array

            $this->fixErrorsLabels($object, $attribute, $key);
        }
        $object->$attribute = $attributeArray; // restore attribute array

        // If attribute has errors, show first error
        if ($object->hasErrors($attribute)) {
            $errors = $object->errors[$attribute];
            $firstError = reset($errors);
            $object->clearErrors($attribute);
            $this->addError($object, $attribute, Yii::t('', '{attribute} has an error:').' '.$firstError);
        }
    }

    protected function prepareAttributeArray($object, $attribute)
    {
        $attributeArray = $object->$attribute; // create copy of attribute array
        if (is_array($this->validator)) {
            $attributeArray = array_merge(
                array_fill_keys(array_keys($this->validator), null), 
                array_intersect_key($attributeArray, $this->validator),
                array()
            );
        }
        return $attributeArray;
    }

    protected function fixErrorsLabels($object, $attribute, $key)
    {
        $errors = $object->getErrors($attribute);
        $object->clearErrors($attribute);
        foreach ($errors as $errorMessage) {
            if (is_array($this->validator)) {
                $errorMessage = str_replace($object->getAttributeLabel($attribute), $object->getAttributeLabel($key), $errorMessage);
            } else {
                $errorMessage = str_replace($object->getAttributeLabel($attribute), $object->getAttributeLabel($attribute)."[$key]", $errorMessage);
            }
            $object->addError($attribute, $errorMessage);
        }
        return $this;
    }

    /**
     * Set parameters for validator.
     * @param CValidator $validatorObject
     * @param string $key
     * @return ArrayValidator
     */
    protected function setValidatorParams(CValidator $validatorObject, $key)
    {
        $params = array();
        if (!$this->separateParams) {
            $params = $this->params;
        } elseif (isset($this->params[$key])) {
            $params = $this->params[$key];
        }
        foreach($params as $paramName => $paramValue) {
            $validatorObject->$paramName = $paramValue;
        }
        return $this;
    }
}
