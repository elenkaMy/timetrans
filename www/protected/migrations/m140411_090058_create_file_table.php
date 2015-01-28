<?php

class m140411_090058_create_file_table extends DbCreateTableMigration
{
    protected $table = 'file';

    public function getTableColumns()
    {
        return array(
            'id'            =>  'pk',
            'file'          =>  'string NOT NULL',
            'path'          =>  'string NOT NULL',
            'mime_type'     =>  'string NOT NULL',
            'size'          =>  'integer NOT NULL',
            'ext'           =>  'string NOT NULL',
            'created_at'    =>  'datetime NOT NULL',
            'updated_at'    =>  'datetime NOT NULL',
        );
    }

    public function getIndexes()
    {
        return array(
            'file'          =>  false,
            'path'          =>  true,
        );
    }
}
