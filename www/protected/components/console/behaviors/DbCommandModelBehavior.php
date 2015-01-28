<?php

/**
 * @property ActiveRecord $model
 */
class DbCommandModelBehavior extends AbstractDbCommandBehavior
{
    protected function checkModel($model)
    {
        if (!($model instanceof ActiveRecord)) {
            throw new CException('Incorrect type of model param. An instance of ActiveRecord is required.');
        }
        return $this;
    }

    /**
     * @param array $attributes field => value array for where conditions.
     * @param DbCriteria $criteria for criteria constructor.
     * @return array of rows. Each row is an associative array in field => value format.
     */
    public function getRows(array $attributes = array(), DbCriteria $criteria = null)
    {
        return array_map(function (ActiveRecord $row) {
            return $row->getAttributes(null);
        }, $this->model->findAll($this->createCriteriaWithFieldsCondition($attributes, $criteria)));
    }

    public function getTableName()
    {
        return $this->model->tableName();
    }

    /**
     * @param array $fields in fieldName => fieldValue format.
     * @return integer count of inserted rows
     */
    public function insertRow(array $fields = array())
    {
        $class = get_class($this->model);
        /* @var $row ActiveRecord */
        $row = new $class($this->owner->modelInsertScenario);

        if ($this->owner->validateModel) {
            $row->attributes = $fields;
        } else {
            foreach ($fields as $fieldName => $fieldValue) {
                $row->{$fieldName} = $fieldValue;
            }
        }

        return $row->saveWithTransaction($this->owner->validateModel) ? 1 : 0;
    }

    /**
     * @param array $fields field => value array for SET block.
     * @param DbCriteria $criteria for criteria constructor.
     * @return integer count of affected rows
     */
    public function updateRows(array $fields = array(), DbCriteria $criteria = null)
    {
        $rows = $this->model->findAll($this->createCriteriaWithFieldsCondition(array(), $criteria));

        foreach ($rows as $row) {
            $row->scenario = $this->owner->modelUpdateScenario;
            if ($this->owner->validateModel) {
                $row->attributes = $fields;
            } else {
                foreach ($fields as $fieldName => $fieldValue) {
                    $row->{$fieldName} = $fieldValue;
                }
            }
        }

        $self = $this;
        $transaction = $this->owner->db->beginTransaction();
        try {
            $result = array_sum(array_map(function (ActiveRecord $r) use ($self) {
                return $r->save($self->owner->validateModel);
            }, $rows));
        } catch (Exception $ex) {
            $transaction->rollback();
            throw $ex;
        }

        $transaction->commit();
        return $result;
    }

    /**
     * @param array $fields field => value array for where conditions.
     * @param DbCriteria $criteria for criteria constructor.
     * @return integer count of affected rows
     */
    public function deleteRows(array $fields = array(), DbCriteria $criteria = null)
    {
        $rows = $this->model->findAll($this->createCriteriaWithFieldsCondition($fields, $criteria));

        foreach ($rows as $row) {
            $row->scenario = $this->owner->modelDeleteScenario;
        }

        $transaction = $this->owner->db->beginTransaction();
        try {
            $result = array_sum(array_map(function (ActiveRecord $r) {
                return $r->delete();
            }, $rows));
        } catch (Exception $ex) {
            $transaction->rollback();
            throw $ex;
        }

        $transaction->commit();
        return $result;
    }
}
