<?php

class m140925_143938_fill_menu_main extends DbMigration
{
    private $_table = '{{menu}}';
    
    public function safeUp()
    {
        $this->insert($this->_table, array(
            'fixed_name'    =>  'main_menu',
            'label'         =>  'Main menu',
        ));
    }

    public function safeDown()
    {
        $this->delete($this->_table, 'fixed_name = "main_menu"');
    }
}
