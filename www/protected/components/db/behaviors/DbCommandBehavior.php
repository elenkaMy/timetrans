<?php

/**
 * @property CDbCommand $owner
 * @method CDbCommand getOwner()
 */
class DbCommandBehavior extends CBehavior
{
    public function attach($owner)
    {
        if (!($owner instanceof CDbCommand)) {
            throw new CException('DbCommandBehavior can be attached only to CDbCommand objects');
        }
        return parent::attach($owner);
    }

    public function createTemporaryTable($table, $columns, $options = null)
    {
        $connection = $this->owner->getConnection();
        $sql = $connection->getSchema()->createTemporaryTable($table, $columns, $options);
        return $this->owner->setText($sql)->execute();
    }
}
