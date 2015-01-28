<?php

class m140401_120457_create_menu_table extends DbMigration
{
    private $_tableName = '{{menu}}';

    public function up()
    {
        $this->createTable($this->_tableName, array(
            'id'            =>  'pk',
            'fixed_name'    =>  'string NOT NULL',
            'label'         =>  'string NOT NULL',
        ));
        $this->createIndex('idx_menu_label', $this->_tableName, 'label');
    }

    public function down()
    {
        $this->dropTable($this->_tableName);
        return true;
    }
}
