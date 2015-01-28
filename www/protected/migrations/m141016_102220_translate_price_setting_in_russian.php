<?php

class m141016_102220_translate_price_setting_in_russian extends DbMigration
{
    public function safeUp()
    {
        $this->update('{{setting}}', array(
            'label' =>  'Цена',
        ), 'fixed_name = "price"');
    }

    public function safeDown()
    {
        $this->update('{{setting}}', array(
            'label' => 'Price',
        ), 'fixed_name = "price"');
    }
}
