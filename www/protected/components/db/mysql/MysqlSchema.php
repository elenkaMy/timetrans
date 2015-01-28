<?php

class MysqlSchema extends CMysqlSchema
{
    protected function createCommandBuilder()
    {
        Yii::import('application.components.db.mysql.MysqlCommandBuilder');
        return new MysqlCommandBuilder($this);
    }

    /**
     * @param array $column
     * @return CMysqlColumnSchema
     */
    protected function createColumn($column)
    {
        $column = parent::createColumn($column);
        if ($column->autoIncrement) {
            $column->type = 'integer';
        } elseif (stripos($column->dbType, 'decimal') !== false) {
            $column->type = 'float';
        }
        return $column;
    }
}
