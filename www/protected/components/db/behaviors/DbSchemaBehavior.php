<?php

/**
 * @property CDbSchema $owner
 * @method CDbSchema getOwner()
 */
class DbSchemaBehavior extends CBehavior
{
    public function attach($owner)
    {
        if (!($owner instanceof CDbSchema)) {
            throw new CException('DbSchemaBehavior can be attached only to CDbSchema objects');
        }
        return parent::attach($owner);
    }

    public function createTemporaryTable($table, $columns, $options = null)
    {
        $sql = $this->owner->createTable($table, $columns, $options);
        $sql = preg_replace('/create[\s]+table/i', 'CREATE TEMPORARY TABLE', $sql);
        return $sql;
    }
}
