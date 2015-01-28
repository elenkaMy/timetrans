<?php

class m140416_115030_create_pack_file_table extends DbCreateTableMigration
{
    protected $table = 'pack_file';

    public function getTableColumns()
    {
        return array(
            'id'            =>  'pk',
            'created_at'    =>  'datetime NOT NULL',
            'updated_at'    =>  'datetime NOT NULL',
        );
    }
}
