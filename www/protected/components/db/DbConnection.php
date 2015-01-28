<?php


class DbConnection extends CDbConnection
{
    public $allowMultipleTransactions = false;

    /**
     * Set character sets if not false. True meaning setting value from $charset.
     * String value meaning setting that value.
     * This property is only used for MySQL.
     * @var boolean|string
     */
    public $charsetVarsValue = true;

    public function init()
    {
        // fix mysql drivers
        $this->driverMap['mysql'] = 'application.components.db.mysql.MysqlSchema';
        $this->driverMap['mysqli'] = 'application.components.db.mysql.MysqlSchema';

        // fix mssql drivers
        $this->driverMap['mssql'] = 'application.components.db.mssql.MssqlSchema';
        $this->driverMap['dblib'] = 'application.components.db.mssql.MssqlSchema';
        $this->driverMap['sqlsrv'] = 'application.components.db.mssql.MssqlSchema';

        parent::init();
    }

    /**
     * @param PDO $pdo
     */
    protected function initConnection($pdo)
    {
        parent::initConnection($pdo);

        $driver = strtolower($pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
        if (!empty($this->charsetVarsValue) && $driver === 'mysql') {
            $self = $this;
            $sql = 'SET ' . implode(', ', array_map(function($var) use ($pdo, $self) {
                return $var . " = " . 
                    $pdo->quote(
                        $self->charsetVarsValue === true
                        ? $self->charset
                        : $self->charsetVarsValue
                    );
            }, array(
                'character_set_results',
                'character_set_client',
                'character_set_connection',
                'character_set_database',
                'character_set_server',
            ))) . ';';
            $pdo->exec($sql);
        }
    }

    /**
     * Execute some function with transaction commit/rollback wrapper.
     * @param callback $function that should be executed.
     * @param array $params for callback functions.
     * @param boolean $throwExceptions whether this method should throw
     * exceptions or not. If false and exception was throwed, than false will
     * returned.
     * @param mixed $result out result of executed function.
     * @return boolean false if exception throwed, else true.
     * @throws Exception if $throwExceptions == true & exception throwed.
     */
    public function doWithTransaction($function, $params = array(), $throwExceptions = true, &$result = null)
    {
        $transaction = $this->beginTransaction();
        try {
            $result = call_user_func_array($function, $params);
            $transaction->commit();
        } catch (Exception $exc) {
            $transaction->rollback();
            if ($throwExceptions) {
                throw $exc;
            } else {
                return false;
            }
        }
        return true;
    }

    public function createCommand($query = null)
    {
        $command = parent::createCommand($query);
        $command->attachBehavior('DbCommandBehavior', array(
            'class'     =>  'application.components.db.behaviors.DbCommandBehavior',
        ));
        return $command;
    }

    public function getSchema()
    {
        $schema = parent::getSchema();
        if (!$schema->asa('DbSchemaBehavior')) {
            $schema->attachBehavior('DbSchemaBehavior', array(
                'class'     =>  'application.components.db.behaviors.DbSchemaBehavior',
            ));
        }
        return $schema;
    }

    /**
     * Starts a transaction.
     * @return CDbTransaction the transaction initiated
     */
    public function beginTransaction()
    {
        if ($this->currentTransaction && !$this->allowMultipleTransactions) {
            throw new CException('Cannot start new transaction while current not closed.');
        }
        return parent::beginTransaction();
    }
}
