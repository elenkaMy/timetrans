<?php

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "setting".
 *
 * The followings are the available columns in table 'setting':
 * @property integer $id
 * @property string $fixed_name
 * @property string $label
 * @property string $value
 * @property string $setting_type
 * @property integer $can_be_empty
 * @property string $created_at
 * @property string $updated_at
 * @property-read array $valRuleForValueAttr
 *
 * The followings are the available basic CActiveRecord methods:
 * @see CActiveRecord
 * @method Setting find() find($condition = '', array $params = array())
 * @method Setting findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method Setting findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Setting findBySql() findBySql($sql, array $params = array())
 * @method Setting[] findAll() findAll($condition = '', array $params = array())
 * @method Setting[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method Setting[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Setting[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method Setting with() with()
 *
 * --- END ModelDoc ---
 */
class Setting extends ActiveRecord
{
    static private $_fixedData = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Setting the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{setting}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior'    =>  array(
                'class'                 =>  'zii.behaviors.CTimestampBehavior',
                'createAttribute'       =>  'created_at',
                'updateAttribute'       =>  'updated_at',
                'setUpdateOnCreate'     =>  true,
                'timestampExpression'   =>  self::getNowExpression(),
            ),
        );
    }

    protected function beforeDelete()
    {
        if($this->fixed_name !== null){
            return false;
        }
        return parent::beforeDelete();
    }

    /**
     * @param string $name
     * @return Setting
     */
    public static function byFixedName($name)
    {
        if (array_key_exists($name, self::$_fixedData)) {
            return self::$_fixedData[$name];
        }
        return self::$_fixedData[$name] = static::model()->findByAttributes(array(
            'fixed_name'    =>  $name,
        ));
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $result = array_merge($this->can_be_empty ? array() : array(
            array(
                'value', 'required', 'on' => 'adminUpdate_'.$this->setting_type,
            ),
        ), array(
            array(
                'value',
                'length',
                'min' => Yii::app()->params['admin']['min-string-field-size'],
                'max' => Yii::app()->params['admin']['max-string-field-size'],
                'on' => 'adminUpdate_string',
            ),
            array(
                'value',
                'length',
                'max' => Yii::app()->params['admin']['max-text-field-size'],
                'on' => array(
                    'adminUpdate_text',
                    'adminUpdate_ckeditor',
                ),
            ),
            array(
                'value',
                'email',
                'on' => 'adminUpdate_email',
            ),
            array(
                'value',
                'url',
                'defaultScheme' => 'http',
                'on' => 'adminUpdate_url',
            ),
            array(
                'value',
                'numerical',
                'on' => 'adminUpdate_number',
            ),
            array(
                'value',
                'filter',
                'filter' => function ($value) {
                    return ($value === '' || $value === null)
                        ? null
                        : str_replace(array(',', ' '), array('.', ''), $value);
                },
                'on' => 'adminUpdate_number',
            ),
            array(
                'value',
                'numerical',
                'integerOnly' => true,
                'on' => 'adminUpdate_integer',
            ),

            array(
                implode(', ', array(
                    'label',
                    'setting_type',
                    'updated_at',
                )),
                'length',
                'on'            =>  'search',
            ),
        ));

        return $result;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array_map(function ($label) {
            return Yii::t('admin', Yii::t('setting', $label));
        }, array(
            'id'            =>  'ID',
            'fixed_name'    =>  'Fixed Name',
            'label'         =>  'Label',
            'value'         =>  'Value',
            'setting_type'  =>  'Setting Type',
            'can_be_empty'  =>  'Can Be Empty',
            'created_at'    =>  'Created At',
            'updated_at'    =>  'Updated At',
        ));
    }

    /**
     * Apply search conditions
     * @return Setting
     */
    public function search()
    {
        $criteria = new DbCriteria;
        $this->validate();

        // Note: please modify the following code to remove attributes that should not be searched.

        if (!$this->hasErrors('label')) {
            $criteria->compare('label', $this->label, true);
        }
        if (!$this->hasErrors('setting_type')) {
            $criteria->compare('setting_type', $this->setting_type, true);
        }
        if (!$this->hasErrors('updated_at')) {
            $criteria->compare('updated_at', $this->updated_at, true);
        }
        $this->dbCriteria->mergeWith($criteria);
        return $this;
    }
}
