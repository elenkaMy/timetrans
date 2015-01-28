<?php

class m140925_143924_add_default_page extends DbMigration
{
    private $_table = '{{page}}';

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');

        $this->insert($this->_table, array(
            'page_name'     =>  'Главная страница',
            'fixed_name'    =>  'default',
            'alias'         =>  '',
            'created_at'    =>  $now,
            'updated_at'    =>  $now,
        ));
    }

    public function safeDown()
    {
        $this->delete($this->_table, 'fixed_name = "default"');
    }
}
