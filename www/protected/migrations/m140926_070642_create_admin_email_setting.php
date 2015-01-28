<?php

class m140926_070642_create_admin_email_setting extends DbMigration
{
    private $_table = '{{setting}}';
    
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert($this->_table, array(
            'fixed_name'    =>  'adminEmail',
            'label'         =>  'Admin Email',
            'value'         =>  'admin@this-mail-must-be-changed.us',
            'setting_type'  =>  'ckeditor',
            'can_be_empty'  =>  true,
            'created_at'    =>  $now,
            'updated_at'    =>  $now,
        ));
    }

    public function safeDown()
    {
        $this->delete($this->_table, 'fixed_name = "adminEmail"');
    }
}
