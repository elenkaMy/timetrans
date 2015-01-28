<?php

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "Menu".
 *
 * The followings are the available columns in table 'Menu':
 * @property integer $id
 * @property string $fixed_name
 * @property string $label
 *
 * The followings are the available model relations:
 * @property MenuItem[] $menuItems
 * @property MenuItem[] $parentMenuItems
 *
 * The followings are the available basic CActiveRecord methods:
 * @see CActiveRecord
 * @method Menu find() find($condition = '', array $params = array())
 * @method Menu findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method Menu findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Menu findBySql() findBySql($sql, array $params = array())
 * @method Menu[] findAll() findAll($condition = '', array $params = array())
 * @method Menu[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method Menu[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Menu[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method Menu with() with()
 *
 * --- END ModelDoc ---
 */
class Menu extends ActiveRecord
{
    static private $_fixedData = array();

    /**
     * @param string $name
     * @return Menu
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
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Menu the static model class
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
        return '{{menu}}';
    }

    public function primaryKey()
    {
        return 'id';
    }


    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
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
            'menuItems' =>  array(self::HAS_MANY,
                'MenuItem',
                'menu_id',
                'order' => 'menuItems.position',
            ),
            'parentMenuItems' =>  array(self::HAS_MANY,
                'MenuItem',
                'menu_id',
                'condition' => 'parentMenuItems.parent_item_id is null',
                'order'     => 'parentMenuItems.position',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array_map(function ($label) {
            return Yii::t('admin', Yii::t('menu', $label));
        }, array(
            'id'            =>  'ID',
            'fixed_name'    =>  'Fixed Name',
            'label'         =>  'Label',
        ));
    }
}
