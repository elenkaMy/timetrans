<?php

/**
 * This class provides working CActiveRecord with temporary tables.
 * @author Alexey Seynov <sejnovalexey@gmail.com>
 * 
 * @property array $tableColumns
 * @property string|null $tableOptions
 */
abstract class TemporaryActiveRecord extends ActiveRecord
{
    private static $_createdTemporaryTables = array();

    /**
     * You must override this method for setting specified table structure.
     * Be aware, do not use AR properties defined by getters or setters!
     * @return array
     */
    abstract public function getTableColumns();

    /**
     * You may override this method for setting custom table options.
     * Be aware, do not use AR properties defined by getters or setters!
     * @return string|null
     */
    public function getTableOptions()
    {
        $charset = $this->getDbConnection()->charset;
        $charset = $this->getDbConnection()->pdoInstance->quote($charset);

        return implode(' ', array(
            'ENGINE InnoDB',
            'DEFAULT CHARACTER SET '.$charset,
        ));
    }

    /**
     * @param string $tableName
     * @return boolean whether table with current table name created yet.
     */
    public static function isCreated($tableName)
    {
        return isset(self::$_createdTemporaryTables[$tableName]);
    }

    /**
     * This method creates temporary table if that not exists yet.
     */
    protected function initTemporaryTable()
    {
        $tableName = $this->tableName();
        if (!array_key_exists($tableName, self::$_createdTemporaryTables)) {
            self::$_createdTemporaryTables[$tableName] = null; // preventing recursive invokes of {@link getMetaData()} via {@link __get()}
            $this->getDbConnection()->createCommand()->createTemporaryTable($tableName, $this->getTableColumns(), $this->getTableOptions());
            self::$_createdTemporaryTables[$tableName] = $tableName;
            $this->afterCreatingTable();
        } elseif (is_null(self::$_createdTemporaryTables[$this->tableName()])) {
            throw new CException('Getting metadata before creating of temporary table. May be you used properties defined by getters/setters.');
        }
    }

    /**
     * You can override this method for creating specified keys and indexes, or
     * adding your specified logic.
     */
    protected function afterCreatingTable()
    {
        //
    }

    /**
     * Returns the meta-data for this AR. Creates temporary table on the first using.
     * @return CActiveRecordMetaData the meta for this AR class.
     */
    public function getMetaData()
    {
        $this->initTemporaryTable();
        return parent::getMetaData();
    }
}
