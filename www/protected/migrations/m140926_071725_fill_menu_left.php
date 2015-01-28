<?php

class m140926_071725_fill_menu_left extends DbMigration
{
    private $_table = '{{menu}}';
    
    public function safeUp()
    {
        $this->insert($this->_table, array(
            'fixed_name'    =>  'left_menu',
            'label'         =>  'Left menu',
        ));
    }

    public function safeDown()
    {
        $this->delete($this->_table, 'fixed_name = "left_menu"');
    }
}
