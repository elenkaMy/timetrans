<?php

class m141016_102148_create_price_setting extends DbMigration
{
    private $_table = '{{setting}}';
    
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert($this->_table, array(
            'fixed_name'    =>  'price',
            'label'         =>  'Price',
            'value'         =>  'п/м руб.',
            'setting_type'  =>  'ckeditor',
            'can_be_empty'  =>  true,
            'created_at'    =>  $now,
            'updated_at'    =>  $now,
        ));
    }

    public function safeDown()
    {
        $this->delete($this->_table, 'fixed_name = "price"');
    }
}
