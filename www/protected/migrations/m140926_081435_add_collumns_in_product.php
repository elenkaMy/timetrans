<?php

class m140926_081435_add_collumns_in_product extends DbMigration
{
    public function up()
    {
        $this->addColumn('{{product}}', 'pack_file_id', 'integer DEFAULT NULL');
        $this->createIndex('idx_product_pack_file_id', '{{product}}', 'pack_file_id');
        $this->addForeignKey('fk_product_pack_file', '{{product}}', 'pack_file_id', '{{pack_file}}', 'id', 'RESTRICT', 'RESTRICT');
        
        $this->addColumn('{{product}}', 'visible', 'bool NOT NULL DEFAULT "0"');
    }

    public function down()
    {
        $this->dropForeignKey('fk_product_pack_file', '{{product}}');
        $this->dropColumn('{{product}}', 'pack_file_id');
        
        
        $this->dropColumn('{{product}}', 'visible');
        return true;
    }

}
