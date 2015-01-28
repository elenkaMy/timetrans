<?php

class m140331_165649_setting_table extends DbMigration
{
    private $_tableName = '{{setting}}';

    public function up()
    {
        $this->createTable($this->_tableName, array(
            'id'            =>  'pk',
            'fixed_name'    =>  'string NOT NULL',
            'label'         =>  'string NOT NULL',
            'value'         =>  'text NOT NULL',
            'setting_type'  =>  'ENUM("string", "text", "ckeditor", "email") NOT NULL DEFAULT "string"',
            'can_be_empty'  =>  'boolean DEFAULT FALSE',
            'created_at'    =>  'datetime NOT NULL',
            'updated_at'    =>  'datetime NOT NULL',

        ));
        $this->createIndex('idx_setting_label', $this->_tableName, 'label');
    }

    public function down()
    {
        $this->dropTable($this->_tableName);
        return true;
    }
}
