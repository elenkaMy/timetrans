<?php

class CompareValidator extends CCompareValidator
{
    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     * @throws CException if invalid operator is used
     */
    protected function validateAttribute($object, $attribute)
    {
        if (!is_null($this->compareAttribute)) {
            switch (true) {
                case $this->allowEmpty && $this->isEmpty($object->{$this->compareAttribute}):
                case $this->skipOnError && $object->hasErrors($this->compareAttribute):
                    return;
            }
        }

        parent::validateAttribute($object, $attribute);
    }
}
