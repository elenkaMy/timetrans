<?php

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property integer $is_admin
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available basic CActiveRecord methods:
 * @see CActiveRecord
 * @method User find() find($condition = '', array $params = array())
 * @method User findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method User findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method User findBySql() findBySql($sql, array $params = array())
 * @method User[] findAll() findAll($condition = '', array $params = array())
 * @method User[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method User[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method User[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method User with() with()
 *
 * The followings are the available model scopes:
 * @method User recently()
 *
 * --- END ModelDoc ---
 */
class User extends ActiveRecord
{
    public $new_password;
    public $new_password_repeat;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
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
        return '{{user}}';
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

    protected function beforeSave()
    {
        $newPassword = $this->new_password;
        if (!empty($newPassword)) {
            $this->password = CPasswordHelper::hashPassword($this->new_password);
        }

        return parent::beforeSave();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, email', 'required', 'on' => 'adminInsert, adminUpdate'),
            array('username, email', 'length', 'min' => 1, 'max' => 255, 'on' => 'adminInsert, adminUpdate'),
            array('username, email', 'UniqueValidator', 'skipOnError' => true, 'on' => 'adminInsert, adminUpdate'),

            array('is_admin', 'boolean', 'strict' => true, 'on' => 'adminInsert, adminUpdate'),

            array('new_password', 'required', 'on' => 'adminInsert'),
            array('new_password', 'length', 'min' => 3, 'max' => 255, 'on' => 'adminInsert, adminUpdate'),
            array('new_password', 'compare', 'on' => 'adminInsert, adminUpdate'),
            array('new_password_repeat', 'length', 'on' => 'adminInsert, adminUpdate'),

            // The following rule is used by search().
            array(
                implode(', ', array(
                    'username',
                    'email',
                    'created_at',
                )),
                'length',
                'on'            =>  'search',
            ),
            array(
                implode(', ', array(
                    'id',
                )),
                'numerical',
                'integerOnly'   =>  true,
                'on'            =>  'search',
            ),
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
        );
    }

    /**
     * @return array
     */
    public function scopes()
    {
        return array(
            'recently'  =>  array(
                'order'     =>  $this->tableAlias.'.created_at DESC',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array_map(function ($label) {
            return Yii::t('user', $label);
        }, array(
            'id'            =>  'ID',
            'username'      =>  'Username',
            'email'         =>  'Email',
            'password'      =>  'Password',
            'new_password'          =>  'Password',
            'new_password_repeat'   =>  'Confirm Password',
            'is_admin'      =>  'Is Admin',
            'created_at'    =>  'Created At',
            'updated_at'    =>  'Updated At',
        ));
    }

    /**
     * Apply search conditions
     * @return User
     */
    public function search()
    {
        $criteria = new DbCriteria;
        $this->validate();

        if (!$this->hasErrors('id')) {
            $criteria->compare('id', $this->id);
        }
        if (!$this->hasErrors('username')) {
            $criteria->compare('username', $this->username, true);
        }
        if (!$this->hasErrors('email')) {
            $criteria->compare('email', $this->email, true);
        }
        if (!$this->hasErrors('created_at')) {
            $criteria->compare('created_at', $this->created_at, true);
        }

        $this->dbCriteria->mergeWith($criteria);
        return $this;
    }
}
