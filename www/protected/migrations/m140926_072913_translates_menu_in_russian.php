<?php

class m140926_072913_translates_menu_in_russian extends DbMigration
{
    public function safeUp()
    {
        $this->update('{{menu}}', array(
            'label' =>  'Левое меню категорий',
        ), 'fixed_name = "left_menu"');

        $this->update('{{menu}}', array(
            'label' =>  'Основное верхнее меню',
        ), 'fixed_name = "main_menu"');
    }

    public function safeDown()
    {
        $this->update('{{menu}}', array(
            'label' =>  'Left menu',
        ), 'fixed_name = "left_menu"');

        $this->update('{{menu}}', array(
            'label' =>  'Main menu',
        ), 'fixed_name = "main_menu"');
    }
}
