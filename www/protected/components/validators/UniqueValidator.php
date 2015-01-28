<?php

class UniqueValidator extends CUniqueValidator
{
    /**
     * @var array|string comma separated. Additional columns for adding to criteria.
     * For example you can use it when on 2 or more columns added unique index.
     */
    public $multipleColumns = array();

    /**
     * @return array
     */
    protected function getParsedMultipleColumns()
    {
        switch (true) {
            case is_string($this->multipleColumns):
                return array_map('trim', explode(',', $this->multipleColumns));
            case is_array($this->multipleColumns):
                return $this->multipleColumns;
            default:
                return array();
        }
    }

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     * @throws CException if given table does not have specified column name
     */
    protected function validateAttribute($object, $attribute)
    {
        $multipleColumns = $this->getParsedMultipleColumns();

        if (!count($multipleColumns)) {
            return parent::validateAttribute($object, $attribute);
        }

        if ($this->skipOnError) {
            $hasErrors = array_sum(array_map(function ($attr) use ($object) {
                return (int) $object->hasErrors($attr);
            }, $multipleColumns));
            if ($hasErrors) {
                return;
            }
        }

        $oldCriteria = $this->criteria;
        try {
            $this->criteria = new DbCriteria();
            if (is_array($oldCriteria) || ($oldCriteria instanceof CDbCriteria)) {
                $this->criteria->mergeWith($oldCriteria);
            }

            foreach ($multipleColumns as $columnName) {
                $this->criteria->addColumnCondition(array($columnName => $object->{$columnName}));
            }

            $result = parent::validateAttribute($object, $attribute);
        } catch (Exception $ex) {
            $this->criteria = $oldCriteria;
            throw $ex;
        }

        $this->criteria = $oldCriteria;
        return $result;
    }
}
