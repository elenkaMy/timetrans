<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name => CDbColumnSchema)
 * - $labels: list of attribute labels (name => label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name => relation declaration)
 */
?>
<?php echo "<?php\n"; ?>

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "<?php echo $tableName; ?>".
 *
 * The followings are the available columns in table '<?php echo $tableName; ?>':
<?php 
    $isCreatedField = false;
    $isCreatedTimestamp = false;
    $isUpdatedField = false;
    $isUpdatedTimestamp = false;
?>
<?php foreach($columns as $column): ?>
<?php 
    /* @var $column CDbColumnSchema */
    switch ($column->name) {
        case 'created_at':
            $isCreatedField = true;
            break;
        case 'created_at_timestamp':
            $isCreatedTimestamp = true;
            break;
        case 'updated_at':
            $isUpdatedField = true;
            break;
        case 'updated_at_timestamp':
            $isUpdatedTimestamp = true;
            break;
    }
?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name => $relation): ?>
 * @property <?php
    if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
    }
    ?>
<?php endforeach; ?>
<?php endif; ?>
 * --- END ModelDoc ---
 */
class <?php echo $modelClass; ?> extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return <?php echo $modelClass; ?> the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
<?php if($connectionId != 'db'):?>

    /**
     * @return CDbConnection database connection
     */
    public function getDbConnection()
    {
        return Yii::app()-><?php echo $connectionId ?>;
    }
<?php endif?>

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{<?php echo $tableName; ?>}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

<?php $isTimestampFields = $isCreatedTimestamp || $isUpdatedTimestamp ?>
<?php if ($isCreatedField || $isCreatedTimestamp || $isUpdatedField || $isUpdatedTimestamp): ?>
    public function behaviors()
    {
        return array(
<?php if ($isTimestampFields): ?>
            'TimestampBehavior'     =>  array(
                'class'                     =>  'application.components.db.TimestampBehavior',
<?php else: ?>
            'CTimestampBehavior'    =>  array(
                'class'                 =>  'zii.behaviors.CTimestampBehavior',
<?php endif; ?>
<?php if (!$isTimestampFields): ?>
                'createAttribute'       =>  '<?php echo $isCreatedField ? 'created_at' : 'null' ?>',
<?php elseif ($isTimestampFields && !$isCreatedTimestamp): ?>
                'createAttribute'           =>  null,
<?php endif; ?>
<?php if ($isTimestampFields && !$isCreatedField): ?>
                'createDatetimeAttribute'   =>  null,
<?php endif; ?>
<?php if (!$isTimestampFields): ?>
                'updateAttribute'       =>  '<?php echo $isUpdatedField ? 'updated_at' : 'null' ?>',
<?php elseif ($isTimestampFields && !$isUpdatedTimestamp): ?>
                'updateAttribute'           =>  null,
<?php endif; ?>
<?php if ($isTimestampFields && !$isUpdatedField): ?>
                'updateDatetimeAttribute'   =>  null,
<?php endif; ?>
<?php if (!$isTimestampFields && $isCreatedField && $isUpdatedField): ?>
                'setUpdateOnCreate'     =>  true,
<?php elseif($isTimestampFields && !$isUpdatedField && !$isUpdatedTimestamp): ?>
                'setUpdateOnCreate'         =>  false,
<?php endif; ?>
<?php if (!$isTimestampFields): ?>
                'timestampExpression'   =>  self::getNowExpression(),
<?php endif; ?>
            ),
        );
    }
<?php endif; ?>

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
<?php foreach($rules as $rule): ?>
//            <?php
  $rule = str_replace(array(' => ', '=> ', ' =>', '=>'), array('=>', '=>', '=>', ' => '), $rule) . ",";
  $rule = preg_replace('/\),$/', ", 'on' => 'COMMA, SEPARATED, SCENARIOS, HERE'),", $rule);
  echo $rule . "\n";
?>
<?php endforeach; ?>
            // The following rule is used by search().
            // NOTE: Please remove those attributes that should not be searched.
            /*
            array(
                implode(', ', array(
                    <?php echo implode(PHP_EOL.str_pad('', 20), array_map(function ($s) {
                        return "'$s',";
                    }, array_keys(array_filter($columns, function ($c){
                        return $c->type !== 'integer';
                    })))).PHP_EOL; ?>
                )),
                'length',
                'on'            =>  'search',
            ),
            array(
                implode(', ', array(
                    <?php echo implode(PHP_EOL.str_pad('', 20), array_map(function ($s) {
                        return "'$s',";
                    }, array_keys(array_filter($columns, function ($c){
                        return $c->type === 'integer';
                    })))).PHP_EOL; ?>
                )),
                'numerical',
                'integerOnly'   =>  true,
                'on'            =>  'search',
            ),
            */
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
<?php
    $maxNameLength = 2/*'*/ + (count($relations) ? max(array_map('strlen', array_keys($relations))) : 0);
    $maxNameLength += 4 - ($maxNameLength) % 4;
?>
<?php foreach($relations as $name => $relation): ?>
            <?php echo str_pad("'$name'", $maxNameLength) . "=>  $relation,\n"; ?>
<?php endforeach; ?>
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array_map(function ($label) {
            return Yii::t('admin', Yii::t('<?php echo lcfirst($modelClass) ?>', $label));
        }, array(
<?php
    $maxNameLength = 2/*'*/ + (count($labels) ? max(array_map('strlen', array_keys($labels))) : 0);
    $maxNameLength += 4 - ($maxNameLength) % 4;
?>
<?php foreach($labels as $name => $label): ?>
            <?php echo str_pad("'$name'", $maxNameLength) . "=>  '$label',\n"; ?>
<?php endforeach; ?>
        ));
    }

    /*
    Translatings for model labels
<?php foreach($labels as $label): ?>
<?php echo implode("\n", array(
    'msgctxt "'.lcfirst($modelClass).'"',
    'msgid "'.$label.'"',
    'msgstr "'.$label.'"',
    "\n",
));?>
<?php endforeach; ?>
    */

    /*
    Translatings for crud model actions
<?php echo implode("\n", array(
    'msgctxt "admin"',
    'msgid "'.$this->pluralize($modelClass).'"',
    'msgstr "'.$this->pluralize($modelClass).'"',
    '',
    'msgctxt "admin"',
    'msgid "Create '.$modelClass.'"',
    'msgstr "Создать '.$modelClass.'"',
    '',
    'msgctxt "admin"',
    'msgid "Update '.$modelClass.'"',
    'msgstr "Редактировать '.$modelClass.'"',
    '',
    'msgctxt "admin"',
    'msgid "Delete '.$modelClass.'"',
    'msgstr "Удалить '.$modelClass.'"',
    '',
    'msgctxt "admin"',
    'msgid "View '.$modelClass.'"',
    'msgstr "Просмотреть '.$modelClass.'"',
    '',
    'msgctxt "admin"',
    'msgid "List '.$modelClass.'"',
    'msgstr "Список '.$modelClass.'"',
    '',
    'msgctxt "admin"',
    'msgid "View '.$modelClass.' on Web Site"',
    'msgstr "Посмотреть '.$modelClass.'" на сайте',
));?>
    */

    /**
     * Apply search conditions
     * @return <?php echo $this->modelClass ?> 
     */
    public function search()
    {
        $criteria = new DbCriteria;
        $this->validate();

        // Note: please modify the following code to remove attributes that should not be searched.
<?php
foreach($columns as $name=>$column)
{
    echo '//'.str_pad('', 8)."if (!\$this->hasErrors('$name')) {\n";
    if($column->type==='string')
    {
        echo '//'.str_pad('', 12).'$criteria->compare("$this->tableAlias.'.$name.'", $this->'.$name.', true);'."\n";
    }
    else
    {
        echo '//'.str_pad('', 12).'$criteria->compare("$this->tableAlias.'.$name.'", $this->'.$name.');'."\n";
    }
    echo '//'.str_pad('', 8)."}\n";
}
?>

        $this->dbCriteria->mergeWith($criteria);
        return $this;
    }
}
