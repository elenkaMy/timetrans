<?php

/**
 * @method DatabaseCommand getOwner()
 * @property DatabaseCommand $owner
 * @property mixed $model
 * @property string $tableName
 */
abstract class AbstractDbCommandBehavior extends CConsoleCommandBehavior
{
    private $_model;

    public function attach($owner)
    {
        if (!($owner instanceof DatabaseCommand)) {
            throw new CException('DbCommandBehaviors can be attached only for DatabaseCommand');
        }
        return parent::attach($owner);
    }

    /**
     * @param mixed $model
     * @return AbstractDbCommandBehavior
     * @throws CException if model has invalid type
     */
    abstract protected function checkModel($model);

    /**
     * @return mixed
     */
    public function getModel()
    {
        $this->checkModel($this->_model);
        return $this->_model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->checkModel($model);
        $this->_model = $model;
    }

    /**
     * @param array $attributes field => value array for where conditions.
     * @param DbCriteria $criteria
     * @return array of rows. Each row is an associative array in field => value format.
     */
    abstract public function getRows(array $attributes = array(), DbCriteria $criteria = null);

    /**
     * @return string
     */
    abstract public function getTableName();

    /**
     * @param array $fields in fieldName => fieldValue format.
     * @return integer count of inserted rows
     */
    abstract public function insertRow(array $fields = array());

    /**
     * @param array $fields field => value array for SET block.
     * @param DbCriteria $criteria
     * @return integer count of affected rows
     */
    abstract public function updateRows(array $fields = array(), DbCriteria $criteria = null);

    /**
     * @param array $fields field => value array for where conditions.
     * @param DbCriteria $criteria
     * @return integer count of affected rows
     */
    abstract public function deleteRows(array $fields = array(), DbCriteria $criteria = null);

    /**
     * @param array $fields
     * @param DbCriteria $criteria
     * @return DbCriteria
     */
    protected function createCriteriaWithFieldsCondition(array $fields = array(), DbCriteria $criteria = null)
    {
        $criteria = is_null($criteria) ? new DbCriteria : clone $criteria;
        if (count($fields)) {
            $criteria->addColumnCondition($fields);
        }
        return $criteria;
    }
}
