<?php

class m131224_103217_create_user_table extends DbMigration
{
    private $_tableName = '{{user}}';

    public function up()
    {
        $this->createTable($this->_tableName, array(
            'id'            =>     'pk',
            'username'      =>     'string NOT NULL',
            'email'         =>     'string NOT NULL',
            'password'      =>     'string NOT NULL',
            'is_admin'      =>     'boolean NOT NULL DEFAULT 0',
            'created_at'    =>     'datetime NOT NULL',
            'updated_at'    =>     'datetime NOT NULL',
        ));
        $this->createIndex('idx_user_username', $this->_tableName, 'username', true);
        $this->createIndex('idx_user_email', $this->_tableName, 'email', true);
    }

    public function down()
    {
        $this->dropTable($this->_tableName);
        return true;
    }
}
