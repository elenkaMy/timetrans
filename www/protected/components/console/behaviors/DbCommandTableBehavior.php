<?php

/**
 * @property string $model
 */
class DbCommandTableBehavior extends AbstractDbCommandBehavior
{
    protected function checkModel($model)
    {
        if (!is_string($model)) {
            throw new CException('Incorrect type of model param. A string is required.');
        }
        return $this;
    }

    /**
     * @param array $attributes field => value array for where conditions.
     * @param DbCriteria $criteria for create command constructor.
     * @return array of rows. Each row is an associative array in field => value format.
     */
    public function getRows(array $attributes = array(), DbCriteria $criteria = null)
    {
        return $this->owner->db->commandBuilder
            ->createFindCommand(
                $this->tableName,
                $this->createCriteriaWithFieldsCondition($attributes, $criteria)
            )->queryAll();
    }

    public function getTableName()
    {
        return $this->model;
    }

    /**
     * @param array $fields in fieldName => fieldValue format.
     * @return integer count of inserted rows
     */
    public function insertRow(array $fields = array())
    {
        return $this->owner->db->commandBuilder
            ->createInsertCommand($this->tableName, $fields)
            ->execute();
    }

    /**
     * @param array $fields field => value array for SET block.
     * @param DbCriteria $criteria for create command constructor.
     * @return integer count of affected rows
     */
    public function updateRows(array $fields = array(), DbCriteria $criteria = null)
    {
        return $this->owner->db->commandBuilder
            ->createUpdateCommand(
                $this->tableName,
                $fields,
                $this->createCriteriaWithFieldsCondition(array(), $criteria)
            )->execute();
    }

    /**
     * @param array $fields field => value array for where conditions.
     * @param DbCriteria $criteria for create command constructor.
     * @return integer count of affected rows
     */
    public function deleteRows(array $fields = array(), DbCriteria $criteria = null)
    {
        return $this->owner->db->commandBuilder
            ->createDeleteCommand(
                $this->tableName,
                $this->createCriteriaWithFieldsCondition($fields, $criteria)
            )->execute();
    }
}
