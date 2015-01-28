<?php

/**
 * @property-read string $tableName
 * @property-read array $tableColumns in name => type format.
 * @property-read array $foreignKeys  array of arrays. Each array
 * must have items'columns', 'refTable', 'refColumns'. Optionally may have
 * items 'onDelete', 'onUpdate'. By default 'RESTRICT'. If key is string
 * it will be used in fk and index names.
 * Optionally may have item 'unique' for auto created index, false by default.
 * @property-read array $indexes in field|fields => isUnique format.
 */
abstract class DbCreateTableMigration extends DbMigration
{
    const MAX_NAME_LENGTH = 64;

    const FOREIGN_KEY_PREFIX = 'fk_';
    const FOREIGN_KEY_INDEX_PREFIX = 'fkidx_';
    const INDEX_PREFIX = 'idx_';

    /**
     * @var string table name. By default it will wrap by '{{}}' {@see getTableName()}.
     */
    protected $table;

    /**
     * @var bool when true, than indexes will auto create before creating FKs.
     */
    protected $createIndexesBeforeAddingFK = true;

    /**
     * @var boolean when true all table names will wrap by '{{}}'.
     */
    protected $autoWrapTableNames = true;

    /**
     * @return array in column name => column type format.
     */
    abstract public function getTableColumns();

    /**
     * @return array of arrays. Each array must have items 'columns', 'refTable', 'refColumns'.
     * Optionally may have items 'onDelete', 'onUpdate'. By default 'RESTRICT'.
     * Optionally may have item 'unique' for auto created index, false by default.
     * If key is string it will be used in fk and index names.
     */
    public function getForeignKeys()
    {
        return array();
    }

    /**
     * @return array in field|fields => isUnique format.
     */
    public function getIndexes()
    {
        return array();
    }

    protected function wrapTableName($tableName)
    {
        if (!$this->autoWrapTableNames) {
            return $tableName;
        } elseif (substr($tableName, 0, 2) === '{{' && substr($tableName, -2) === '}}') {
            return $tableName;
        } else {
            return '{{'.$tableName.'}}';
        }
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        if (is_null($this->table)) {
            throw new CException('You must define table name.');
        }
        return $this->wrapTableName($this->table);
    }

    /**
     * @return DbCreateTableMigration
     * @throws CException
     */
    protected function validateTableColumns()
    {
        foreach (array_keys($this->tableColumns) as $columnField) {
            if (is_int($columnField)) {
                throw new CException('Table columns must be in a fieldName => fieldType format!');
            }
        }
        return $this;
    }

    /**
     * @return DbCreateTableMigration
     * @throws CException
     */
    protected function validateForeignKeys()
    {
        foreach ($this->foreignKeys as $key => $fk) {
            if (is_string($key) && strlen($key) > static::MAX_NAME_LENGTH) {
                throw new CException('Foreign key name length cannot be greater than '.static::MAX_NAME_LENGTH.'!');
            }

            if (!is_array($fk) || !isset($fk['columns'], $fk['refTable'], $fk['refColumns'])) {
                throw new CException('Each FK must be an array that contains columns, refTable & refColumns items');
            }

            foreach (array('onDelete', 'onUpdate') as $checkProperty) {
                if (!array_key_exists($checkProperty, $fk)) {
                    continue;
                }
                if (is_string($fk[$checkProperty])) {
                    $fk[$checkProperty] = strtoupper($fk[$checkProperty]);
                }
                if (!in_array($fk[$checkProperty], array(null, 'RESTRICT', 'SET NULL', 'CASCADE', 'NO ACTION'), true)) {
                    throw new CException('Incorrect FK '.$checkProperty.' value!');
                }
            }

            if (isset($fk['unique']) && !is_bool($fk['unique'])) {
                throw new CException('Incorrect unique param for FK.');
            }

            $otherKeys = array_diff_key($fk, array_fill_keys(array(
                'columns',
                'refTable',
                'refColumns',
                'onDelete',
                'onUpdate',
                'unique',
            ), null));
            if (count($otherKeys)) {
                reset($otherKeys);
                throw new CException('Unknown key '.key($otherKeys).' in FK config.');
            }
        }
        return $this;
    }

    /**
     * @return DbCreateTableMigration
     * @throws CException
     */
    protected function validateIndexes()
    {
        foreach ($this->indexes as $fields => $isUnique) {
            if (!is_string($fields)) {
                throw new CException('Incorrect field names in indexes config.');
            }
            if (!is_bool($isUnique)) {
                throw new CException('Incorrect isUnique values in indexes config.');
            }
        }
        return $this;
    }

    /**
     * @throws CException
     * @return DbCreateTableMigration
     */
    protected function validateTableConfig()
    {
        return $this
            ->validateTableColumns()
            ->validateForeignKeys()
            ->validateIndexes()
        ;
    }

    /**
     * @param string|array $columns
     * @param string $prefix
     * @param string $table2
     * @param string|array $columns2
     * @return string
     */
    protected function generateNameByColumns($columns, $prefix, $table2 = null, $columns2 = null)
    {
        if (!is_array($columns)) {
            $columns = array_map('trim', explode(',', $columns));
        }
        $columns = implode('__', $columns);
        $tableName = str_replace(array('{{', '}}', '-'), array('', '', '_'), $this->tableName);
        $result = $prefix.$tableName.'___'.$columns;

        if (!is_null($table2)) {
            $table2 = str_replace(array('{{', '}}', '-'), array('', '', '_'), $this->wrapTableName($table2));
            $result .= '___'.$table2;
        }
        if (!is_null($columns2)) {
            if (!is_array($columns2)) {
                $columns2 = array_map('trim', explode(',', $columns2));
            }
            $columns2 = implode('__', $columns2);
            $result .= '___'.$columns2;
        }

        if (strlen($result) > static::MAX_NAME_LENGTH) {
            $hash = sha1($result);
            $result = substr($result, 0, static::MAX_NAME_LENGTH - 3 - strlen($hash)) . "___$hash";
        }

        return $result;
    }

    public function up()
    {
        $this->validateTableConfig();

        $this->createTable($this->tableName, $this->tableColumns);

        foreach ($this->indexes as $indexFields => $unique) {
            $this->createIndex($this->generateNameByColumns($indexFields, static::INDEX_PREFIX), $this->tableName, $indexFields, $unique);
        }

        $generatedColumns = array();
        foreach ($this->foreignKeys as $key => $fk) {
            $columns = implode(', ', (array) $fk['columns']);
            $refTable = $this->wrapTableName($fk['refTable']);
            $refColumns = implode(', ', (array) $fk['refColumns']);
            $onUpdate = array_key_exists('onUpdate', $fk) ? $fk['onUpdate'] : 'RESTRICT';
            $onDelete = array_key_exists('onDelete', $fk) ? $fk['onDelete'] : 'RESTRICT';
            $unique = @$fk['unique'] ?: false;

            if (is_string($key)) {
                $indexName = $key;
                $fkName = $key;
            } else {
                $indexName = $this->generateNameByColumns($columns, static::FOREIGN_KEY_INDEX_PREFIX);
                $fkName = $this->generateNameByColumns($columns, static::FOREIGN_KEY_PREFIX, $refTable, $refColumns);
            }

            if ($this->createIndexesBeforeAddingFK) {
                if (!array_key_exists($columns, $generatedColumns)) {
                    $this->createIndex($indexName, $this->tableName, $columns, $unique);
                    $generatedColumns[$columns] = true;
                }
            }
            $this->addForeignKey($fkName, $this->tableName, $columns, $refTable, $refColumns, $onDelete, $onUpdate);
        }
    }

    public function down()
    {
        $this->validateTableConfig();

        foreach ($this->foreignKeys as $key => $fk) {
            $columns = implode(', ', (array) $fk['columns']);
            $refTable = $this->wrapTableName($fk['refTable']);
            $refColumns = implode(', ', (array) $fk['refColumns']);

            if (is_string($key)) {
                $fkName = $key;
            } else {
                $fkName = $this->generateNameByColumns($columns, static::FOREIGN_KEY_PREFIX, $refTable, $refColumns);
            }

            $this->dropForeignKey($fkName, $this->tableName);
        }

        $this->dropTable($this->tableName);

        return true;
    }
}
