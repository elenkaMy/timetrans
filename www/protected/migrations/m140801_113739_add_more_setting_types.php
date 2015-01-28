<?php

class m140801_113739_add_more_setting_types extends DbMigration
{
    private $_table = '{{setting}}';
    private $_column = '{{setting_type}}';

    public function up()
    {
        $this->alterColumn($this->_table, $this->_column, 'ENUM("string", "text", "ckeditor", "email", "url", "number", "integer") NOT NULL DEFAULT "string"');
    }

    public function down()
    {
        $this->delete($this->_table, array(
            'IN',
            $this->_column,
            array(
                'url',
                'number',
                'integer',
            ),
        ));
        $this->alterColumn($this->_table, $this->_column, 'ENUM("string", "text", "ckeditor", "email") NOT NULL DEFAULT "string"');
        return true;
    }
}
