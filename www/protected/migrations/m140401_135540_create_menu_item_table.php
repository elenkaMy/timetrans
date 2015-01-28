<?php

class m140401_135540_create_menu_item_table extends DbMigration
{
    private $_table = '{{menu_item}}';

    public function up()
    {
        $this->createTable($this->_table, array(
            'id'               =>  'pk',
            'parent_item_id'   =>  'integer DEFAULT NULL',
            'menu_id'          =>  'integer NOT NULL',
            'item_name'        =>  'string NOT NULL',
            'item_type'        =>  'string NOT NULL',
            'value'            =>  'string NOT NULL',
            'position'         =>  'integer NOT NULL',
            'link_options'     =>  'text DEFAULT NULL',
            'item_options'     =>  'text DEFAULT NULL',
            'created_at'       =>  'datetime NOT NULL',
            'updated_at'       =>  'datetime NOT NULL',
        ));
        $this->createIndex('idx_item_name', $this->_table, 'item_name');
        $this->createIndex('idx_menu_position', $this->_table, 'menu_id, position');
        $this->createIndex('idx_menu_id', $this->_table, 'menu_id');
        $this->addForeignKey('fk_menu_item_menu', $this->_table, 'menu_id', '{{menu}}', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('idx_parent_item_id', $this->_table, 'parent_item_id');
        $this->addForeignKey('fk_menu_item_menu_item', $this->_table, 'parent_item_id', $this->_table, 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_menu_item_menu', $this->_table);
        $this->dropForeignKey('fk_menu_item_menu_item', $this->_table);
        $this->dropTable($this->_table);
        return true;
    }
}
