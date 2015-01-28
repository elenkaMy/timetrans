<?php

class m141016_080847_translate_text_mein_in_russian extends DbMigration
{
    public function safeUp()
    {
        $this->update('{{setting}}', array(
            'label' =>  'Текст на главной странице',
        ), 'fixed_name = "text_main"');
    }

    public function safeDown()
    {
        $this->update('{{setting}}', array(
            'label' => 'Text on main page',
        ), 'fixed_name = "text_main"');
    }
}
