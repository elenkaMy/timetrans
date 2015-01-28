<?php

class MssqlSchema extends CMssqlSchema
{
    /**
     * Creates a command builder for the database.
     * This method overrides parent implementation in order to create a MSSQL specific command builder
     * @return CDbCommandBuilder command builder instance
     */
    protected function createCommandBuilder()
    {
        Yii::import('application.components.db.mssql.MssqlCommandBuilder');
        return new MssqlCommandBuilder($this);
    }

    public function createTemporaryTable($table, $columns, $options = null)
    {
        $sql = $this->createTable($table, $columns, $options);
        $sql = preg_replace('/create[\s]+table/i', 'CREATE TABLE #TEMP', $sql);
        return $sql;
    }
}
