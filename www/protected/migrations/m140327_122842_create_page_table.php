<?php

class m140327_122842_create_page_table extends DbMigration
{
    private $_table = '{{page}}';

    public function up()
    {
        $this->createTable($this->_table, array(
            'id'                    =>     'pk',
            'parent_page_id'        =>     'integer DEFAULT NULL',
            'page_name'             =>     'string NOT NULL',
            'fixed_name'            =>     'string DEFAULT NULL UNIQUE',
            'alias'                 =>     'string NOT NULL',
            'content'               =>     'text DEFAULT NULL',
            'short_content'         =>     'text DEFAULT NULL',
            'seo_title'             =>     'string DEFAULT NULL',
            'seo_description'       =>     'text DEFAULT NULL',
            'seo_keywords'          =>     'text DEFAULT NULL',
            'position'              =>     'integer NOT NULL',
            'created_at'            =>     'datetime NOT NULL',
            'updated_at'            =>     'datetime NOT NULL',
        ));
        $this->createIndex('idx_page_name', $this->_table, 'page_name');
        $this->createIndex('idx_parent_page_id', $this->_table, 'parent_page_id');
        $this->addForeignKey('fk_page_page', $this->_table, 'parent_page_id', $this->_table, 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_page_page', $this->_table);
        $this->dropTable($this->_table);
        return true;
    }
}
