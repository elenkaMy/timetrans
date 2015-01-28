<?php

class m140925_145153_create_head_setting extends DbMigration
{
    private $_table = '{{setting}}';

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        
        $this->insert($this->_table, array(
            'fixed_name'    =>  'contacts',
            'label'         =>  'Contacts',
            'value'         =>  '
                Контактные телефоны:
                +7 906 738 90 40
                +7 926 520 90 00
                2133981@gmail.com
            ',
            'setting_type'  =>  'ckeditor',
            'can_be_empty'  =>  true,
            'created_at'    =>  $now,
            'updated_at'    =>  $now,
        ));
        
        $this->insert($this->_table, array(
            'fixed_name'    =>  'contacts_footer_right',
            'label'         =>  'Contacts (right-footer)',
            'value'         =>  '
                ©2013 “Кованые изделия”
                Телефоны:+7 906 738 90 40
            ',
            'setting_type'  =>  'ckeditor',
            'can_be_empty'  =>  true,
            'created_at'    =>  $now,
            'updated_at'    =>  $now,
        ));
        
        $this->insert($this->_table, array(
            'fixed_name'    =>  'contacts_footer_left',
            'label'         =>  'Contacts (left-footer)',
            'value'         =>  '
                Адрес:МО, Ленинский район , пос. Молоково, ул. Ленина 77
                E-mail: 2133981@gmail.com
            ',
            'setting_type'  =>  'ckeditor',
            'can_be_empty'  =>  true,
            'created_at'    =>  $now,
            'updated_at'    =>  $now,
        ));
    }

    public function safeDown()
    {
        $this->delete($this->_table, 'fixed_name = "contacts"');
        $this->delete($this->_table, 'fixed_name = "contacts_footer_right"');
        $this->delete($this->_table, 'fixed_name = "contacts_footer_left"');
    }
}
