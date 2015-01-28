<?php

class m140522_154617_create_product_table extends DbMigration
{
    private $_table = '{{product}}';

    public function up()
    {
        $this->createTable($this->_table, array(
            'id'                =>      'pk',
            'category_id'       =>      'integer NOT NULL',
            'file_id'           =>      'integer NOT NULL',
            'product_name'      =>      'string NOT NULL',
            'alias'             =>      'string NOT NULL',
            'content'           =>      'text DEFAULT NULL',
            'short_content'     =>      'text DEFAULT NULL',
            'price'             =>      'float NOT NULL',
            'seo_title'         =>      'string DEFAULT NULL',
            'seo_description'   =>      'text DEFAULT NULL',
            'seo_keywords'      =>      'text DEFAULT NULL',
            'position'          =>      'integer DEFAULT NULL',
            'created_at'        =>      'datetime NOT NULL',
            'updated_at'        =>      'datetime NOT NULL',
        ));
        $this->createIndex('idx_product_name', $this->_table, 'product_name');
        $this->createIndex('idx_product_category_alias', $this->_table, 'category_id, alias', true);
        $this->createIndex('idx_product_position', $this->_table, 'position');
        $this->createIndex('idx_product_category_position', $this->_table, 'category_id, position');

        $this->createIndex('idx_category_id', $this->_table, 'category_id');
        $this->addForeignKey('fk_product_product_category', $this->_table, 'category_id', '{{product_category}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('idx_file_id', $this->_table, 'file_id');
        $this->addForeignKey('fk_product_file', $this->_table, 'file_id', '{{file}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_product_product_category', $this->_table);
        $this->dropForeignKey('fk_product_file', $this->_table);
        $this->dropTable($this->_table);
        return true;
    }
}
