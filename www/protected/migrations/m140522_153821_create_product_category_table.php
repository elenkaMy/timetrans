<?php

class m140522_153821_create_product_category_table extends DbMigration
{
    private $_table = '{{product_category}}';

    public function up()
    {
        $this->createTable($this->_table, array(
            'id'                    =>     'pk',
            'parent_category_id'    =>     'integer DEFAULT NULL',
            'file_id'               =>     'integer NOT NULL',
            'category_name'         =>     'string NOT NULL',
            'alias'                 =>     'string NOT NULL',
            'fixed_name'            =>     'string DEFAULT NULL UNIQUE',
            'content'               =>     'text DEFAULT NULL',
            'short_content'         =>     'text DEFAULT NULL',
            'seo_title'             =>     'string DEFAULT NULL',
            'seo_description'       =>     'text DEFAULT NULL',
            'seo_keywords'          =>     'text DEFAULT NULL',
            'position'              =>     'integer DEFAULT NULL',
            'created_at'            =>     'datetime NOT NULL',
            'updated_at'            =>     'datetime NOT NULL',
        ));
        $this->createIndex('idx_category_name', $this->_table, 'category_name');
        $this->createIndex('idx_parent_alias', $this->_table, 'parent_category_id, alias', true);
        $this->createIndex('idx_category_position', $this->_table, 'position');
        $this->createIndex('idx_parent_category_position', $this->_table, 'parent_category_id, position');

        $this->createIndex('idx_parent_category_id', $this->_table, 'parent_category_id');
        $this->addForeignKey('fk_category_category', $this->_table, 'parent_category_id', $this->_table, 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('idx_file_id', $this->_table, 'file_id');
        $this->addForeignKey('fk_category_file', $this->_table, 'file_id', '{{file}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_category_category', $this->_table);
        $this->dropForeignKey('fk_category_file', $this->_table);
        $this->dropTable($this->_table);
        return true;
    }

}
