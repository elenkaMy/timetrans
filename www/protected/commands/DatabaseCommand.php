<?php

/**
 * Yiic command to manage database rows
 * Example of using:
 *  <pre>
 *      php www/protected/yiic database view --model=User select=id,username limit=5 is_active=1 condition=some_field\>5
 *  </pre>
 * or:
 *  <pre>
 *      php www/protected/yiic database view --model={{user}} select=id,username limit=5 is_active=1 where=some_field\>5
 *  </pre>
 * or:
 *  <pre>
 *      php www/protected/yiic database insert --model={{user}} username=test email=some@email.com is_active=0
 *  </pre>
 * or:
 *  <pre>
 *      php www/protected/yiic database update --model={{user}} username=test email=some@email.com where=id=5
 *  </pre>
 * or:
 *  <pre>
 *      php www/protected/yiic database delete --model=User id=10 condition=some_field\>=5
 *  </pre>
 * 
 * 
 * @property-read DbConnection $db
 * 
 * Methods and properties from behaviors:
 * @method mixed getModel()
 * @method mixed setModel(mixed $model)
 * @property mixed $model
 * @method array getRows(array $attributes = array(), DbCriteria $criteria = null)
 * @property-read array $rows
 * @property-read array $optionKeys
 * @method string getTableName()
 * @property-read string $tableName
 * @method integer insertRow(array $fields = array())
 * @method integer updateRows(array $fields = array(), DbCriteria $criteria = null)
 * @method integer deleteRows(array $fields = array(), DbCriteria $criteria = null)
 */
class DatabaseCommand extends AbstractCommand
{
    /**
     * @var string|array
     */
    public $importPaths = array(
        'application.models.*',
        'application.components.console.behaviors.*'
    );

    public $dbComponent = 'db';

    public $validateModel = true;
    public $modelInsertScenario = 'insert';
    public $modelUpdateScenario = 'update';
    public $modelDeleteScenario = 'delete';

    public $verbose = true;

    public $simpleStructureKeys = array(
        'schemaName',
        'name',
        'rawName',
        'primaryKey',
        'sequenceName',
        'foreignKeys',
        'columns',
    );

    /**
     * @return array possible option keys for create command constructor.
     */
    public function getOptionKeys()
    {
        return array(
            'select',
            'distinct',
            'condition',
            'params',
            'limit',
            'offset',
            'order',
            'group',
            'having',
            'scopes',
        );
    }

    /**
     * @return DbConnection
     */
    public function getDb()
    {
        return Yii::app()->{$this->dbComponent};
    }

    /**
     * @param mixed $model
     * @return ActiveRecord|string Model or table name.
     */
    protected function getTableNameOrModel($model)
    {
        if ($model instanceof ActiveRecord) {
            return $model;
        } elseif (@class_exists($model) && is_subclass_of($model, 'ActiveRecord')) {
            return call_user_func(array($model, 'model'));
        } else {
            return $model;
        }
    }

    protected function beforeAction($action, $params)
    {
        foreach ((array) $this->importPaths as $path) {
            Yii::import($path);
        }

        if (isset($params[0])) {
            $table = $this->getTableNameOrModel($params[0]);
            $behaviorClass = ($table instanceof ActiveRecord)
                ? 'DbCommandModelBehavior'
                : 'DbCommandTableBehavior';
            $this->attachBehavior('dbCommand', array(
                'class'         =>  $behaviorClass,
                'model'         =>  $table,
            ));
        }

        return parent::beforeAction($action, $params);
    }

    protected function parseAnonymousArgs($args)
    {
        $result = array();
        foreach ($args as $arg) {
            if (strpos($arg, '=') === false) {
                throw new CException("Incorrect argument $arg");
            }
            $arg = array_map('trim', explode('=', $arg));
            $key = array_shift($arg);
            $value = implode('=', $arg);
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * @param string $args
     * @return array
     */
    protected function getModelAttributes($args)
    {
        return array_diff_key($this->parseAnonymousArgs($args), array('model' => true), array_fill_keys($this->optionKeys, true));
    }

    /**
     * @param string $args
     * @return array
     */
    protected function getModelOptions($args)
    {
        return array_intersect_key($this->parseAnonymousArgs($args), array_fill_keys($this->optionKeys, true));
    }

    /**
     * @param string $args
     * @return DbCriteria
     */
    protected function createModelCriteria($args)
    {
        return new DbCriteria($this->getModelOptions($args));
    }

    public function actionView($model, $args = array())
    {
        $rows = $this->getRows($this->getModelAttributes($args), $this->createModelCriteria($args));
        if (!count($rows)) {
            $this->printf('Table is empty');
            return;
        }

        $firstOutput = false;
        foreach ($rows as $row) {
            if (!$firstOutput) {
                $firstOutput = true;
                $this->printf(implode(' | ', array_keys($row)));
            }
            $this->printf(implode(' | ', $row));
        }
    }

    public function actionInsert($model, $args)
    {
        $this->verbose('Row with fields:');
        $this->verbose(print_r($this->parseAnonymousArgs($args), true));
        $this->verbose("will insert in the table '{$this->tableName}'");

        if (!$this->confirm('Are you sure?')) {
            return;
        }

        $count = $this->insertRow($this->parseAnonymousArgs($args));
        $this->printf("$count rows was affected");
    }

    public function actionUpdate($model, $args)
    {
        $this->verbose('Rows with criteria\'s options:');
        $this->verbose(print_r($this->getModelOptions($args), true));
        $this->verbose('will update and set following fields values:');
        $this->verbose(print_r($this->getModelAttributes($args), true));

        if (!$this->confirm('Are you sure?')) {
            return;
        }

        $count = $this->updateRows($this->getModelAttributes($args), $this->createModelCriteria($args));
        $this->printf("$count rows was affected");
    }

    public function actionDelete($model, $args)
    {
        $this->verbose('Rows with conditions:');
        $this->verbose(print_r($this->getModelAttributes($args), true));
        $this->verbose('and with criteria\'s options:');
        $this->verbose(print_r($this->getModelOptions($args), true));
        $this->verbose('will delete!');

        if (!$this->confirm('Are you sure?')) {
            return;
        }

        $count = $this->deleteRows($this->getModelAttributes($args), $this->createModelCriteria($args));
        $this->printf("$count rows was affected");
    }

    public function actionSql($sql, $queryAll = false)
    {
        $this->verbose('Following SQL will execute:');
        $this->verbose(print_r($sql, true));

        if (!$this->confirm('Are you sure?')) {
            return;
        }

        $result = 0;
        $self = $this;
        $this->db->doWithTransaction(function () use ($sql, $self, $queryAll) {
            if ($queryAll) {
                return $self->db->createCommand($sql)->queryAll();
            } else {
                return $self->db->createCommand($sql)->execute();
            }
        }, array(), true, $result);

        if (is_array($result)) {
            $count = count($result);
            $this->printf("$count rows was affected");
            foreach ($result as $row) {
                $this->printf(implode(' | ', $row));
            }
        } else {
            $this->printf("$result rows was affected");
        }
    }

    /**
     * @param string|null $model list of all table names will return if null.
     * @param boolean $simple whether simple table schema's fields need to ouput
     */
    public function actionStructure($model = null, $simple = false)
    {
        if (is_null($model)) {
            $this->db->schema->refresh();
            foreach ($this->db->schema->tableNames as $name) {
                $this->printf($name);
            }
        } else {
            $structure = $this->db->schema->getTable($this->tableName, true);
            $result = array();
            foreach ($structure as $key => $value) {
                if ($simple && !in_array($key, $this->simpleStructureKeys, true)) {
                    continue;
                }

                switch ($key) {
                    case 'columns':
                        $result[$key] = !$simple ? $value : array_map(function (CDbColumnSchema $s) {
                            return implode(', ', array(
                                $s->dbType,
                                $s->allowNull ? 'allow null' : 'not null',
                                'default ' . (is_null($s->defaultValue) ? 'NULL' : $s->defaultValue),
                            ));
                        }, $value);
                        break;
                    default:
                        $result[$key] = $value;
                        break;
                }
            }
            $this->printf(print_r($result, true));
        }
    }
}
