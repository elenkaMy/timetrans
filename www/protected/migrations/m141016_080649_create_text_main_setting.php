<?php

class m141016_080649_create_text_main_setting extends DbMigration
{
    private $_table = '{{setting}}';
    
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert($this->_table, array(
            'fixed_name'    =>  'text_main',
            'label'         =>  'Text on main page',
            'value'         =>  '<p>Каждое изделие изготавливается индивидуально, под заказ.&nbsp; Срок от 7-10 дней.</p>
                                <p><strong>КАЧЕСТВО ГАРАНТИРУЕТСЯ</strong></p>
                                <p>Вы можете выбрать понравившуюся модель на сайте, которая может быть изменена с учетом Ваших пожеланий. Возможна работа по вашим эскизам.<br />
                                Или создание эскиза специально для Вас профессиональным художником по ковке.</p>
                                <p><strong>Звоните, чтобы обсудить ВЕЛИКОЛЕПНУЮ ВЕЩЬ, которой Вы вскоре сможете обзавестись!</strong></p>
                                <p>Качественная художественная ковка - это всегда красота и практичность!</p>
                                <p>&nbsp;</p>
                                ',
            'setting_type'  =>  'ckeditor',
            'can_be_empty'  =>  true,
            'created_at'    =>  $now,
            'updated_at'    =>  $now,
        ));
    }

    public function safeDown()
    {
        $this->delete($this->_table, 'fixed_name = "text_main"');
    }
}
