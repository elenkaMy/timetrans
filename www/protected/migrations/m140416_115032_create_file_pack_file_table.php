<?php

class m140416_115032_create_file_pack_file_table extends DbCreateTableMigration
{
    protected $table = 'file_pack_file';

    public function getTableColumns()
    {
        return array(
            'id'            =>  'pk',
            'file_id'       =>  'integer NOT NULL',
            'pack_file_id'  =>  'integer NOT NULL',
            'position'      =>  'integer NOT NULL DEFAULT 0',
            'created_at'    =>  'datetime NOT NULL',
            'updated_at'    =>  'datetime NOT NULL',
        );
    }

    public function getForeignKeys()
    {
        return array(
            array(
                'columns'       =>  'file_id',
                'refTable'      =>  'file',
                'refColumns'    =>  'id',
            ),
            array(
                'columns'       =>  'pack_file_id',
                'refTable'      =>  'pack_file',
                'refColumns'    =>  'id',
            ),
        );
    }

    public function getIndexes()
    {
        return array(
            'file_id, pack_file_id'     =>  false,
            'position'                  =>  false,
            'pack_file_id, position'    =>  false,
        );
    }
}
