<?php

/**
 * Customized base ActiveRecord
 */
abstract class ActiveRecord extends CActiveRecord
{
    public function getMetaData()
    {
        $metadata = parent::getMetaData();

        $primaryKey = $this->primaryKey();
        if (!empty($primaryKey) && $metadata->tableSchema->primaryKey !== $primaryKey) {
            $metadata->tableSchema->primaryKey = $primaryKey;
            /* @var $column CDbColumnSchema */
            foreach ($metadata->tableSchema->columns as $columnName => $column) {
                if (is_array($primaryKey)) {
                    $column->isPrimaryKey = in_array($columnName, $primaryKey);
                } else {
                    $column->isPrimaryKey = $columnName === $primaryKey;
                }
            }
        }

        return $metadata;
    }

    /**
     * @return CDbExpression 
     */
    public static function getNowExpression()
    {
        switch (Yii::app()->db->getDriverName()) {
            case 'mssql':
            case 'dblib':
            case 'sqlsrv':
                $result = new CDbExpression('getDate()');
                break;
            default:
                $result = new CDbExpression('NOW()');
                break;
        }
        return $result;
    }

    /**
     * Save current record with using transaction.
     * 
     * @param boolean $runValidation whether to perform validation before saving the record.
     * If the validation fails, the record will not be saved to database.
     * @param array $attributes list of attributes that need to be saved. Defaults to null,
     * meaning all attributes that are loaded from DB will be saved.
     * @return boolean whether the saving succeeds
     */
    public function saveWithTransaction($runValidation = true, $attributes = null) {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $result = $this->save($runValidation, $attributes);
            $transaction->commit();
        } catch (Exception $exc) {
            $transaction->rollback();
            throw $exc;
        }

        return $result;
    }

    /**
     * Deletes the row corresponding to this active record with using transaction.
     * @return boolean whether the deletion is successful.
     */
    public function deleteWithTransaction() {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $result = $this->delete();
            $transaction->commit();
        } catch (Exception $exc) {
            $transaction->rollback();
            throw $exc;
        }

        return $result;
    }

    public function existsByPk($pk, $condition = '', $params = array())
    {
        $tableKeys = (array)$this->primaryKey();
        $pk = (array) $pk;

        if (count($tableKeys) !== count($pk)) {
            throw new CDbException('Count of columns in table primary key and method param not matched');
        }

        return $this->existsByAttributes(array_combine(array_values($tableKeys), array_values($pk)), $condition, $params);
    }

    public function existsByAttributes($attributes, $condition = '', $params = array())
    {
        $criteria = new DbCriteria();
        foreach ($attributes as $columnName => $value) {
            if (is_array($value)) {
                $criteria->addInCondition($columnName, $value);
            } else {
                $criteria->addColumnCondition(array(
                    $columnName =>  $value,
                ));
            }
        }

        $builder = $this->getCommandBuilder();
        $criteria->mergeWith($builder->createCriteria($condition, $params));

        return $this->exists($criteria);
    }
}
