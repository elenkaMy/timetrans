<?php

class m140926_070711_translates_setting_in_russian extends DbMigration
{
    public function safeUp()
    {
        $this->update('{{setting}}', array(
            'label' =>  'Email администратора',
        ), 'fixed_name = "adminEmail"');
        
        $this->update('{{setting}}', array(
            'label' =>  'Контакты',
        ), 'fixed_name = "contacts"');
        
        $this->update('{{setting}}', array(
            'label' =>  'Контакты (правый нижний угол)',
        ), 'fixed_name = "contacts_footer_right"');
        
        $this->update('{{setting}}', array(
            'label' =>  'Контакты (левый нижний угол)',
        ), 'fixed_name = "contacts_footer_left"');
    }

    public function safeDown()
    {
        $this->update('{{setting}}', array(
            'label' => 'Admin Email',
        ), 'fixed_name = "adminEmail"');
        
        $this->update('{{setting}}', array(
            'label' => 'Contacts',
        ), 'fixed_name = "contacts"');
        
        $this->update('{{setting}}', array(
            'label' => 'Contacts (right-footer)',
        ), 'fixed_name = "contacts_footer_right"');
        
        $this->update('{{setting}}', array(
            'label' => 'Contacts (left-footer)',
        ), 'fixed_name = "contacts_footer_left"');
    }
}
