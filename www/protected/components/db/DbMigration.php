<?php

abstract class DbMigration extends CDbMigration
{
    /**
     * Use or not default table options. Used only for mysql databases.
     * @var boolean
     */
    protected $useDefaultTableOptions = true;

    protected function getDefaultTableOptions()
    {
        $charset = $this->dbConnection->charset;
        $collate = $this->dbConnection->pdoInstance->quote($charset.'_general_ci');
        $charset = $this->dbConnection->pdoInstance->quote($charset);

        return array(
            'ENGINE InnoDB',
            'DEFAULT CHARACTER SET '.$charset,
            'COLLATE '.$collate,
        );
    }

    public function createTable($table, $columns, $options = null)
    {
        if ($this->useDefaultTableOptions && is_null($options)) {
            $driver = strtolower($this->dbConnection->pdoInstance->getAttribute(PDO::ATTR_DRIVER_NAME));
            if ($driver === 'mysql') {
                $options = implode(' ', $this->getDefaultTableOptions());
            }
        }

        parent::createTable($table, $columns, $options);
    }
}
