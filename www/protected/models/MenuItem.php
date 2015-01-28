<?php

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "menu_item".
 *
 * The followings are the available columns in table 'menu_item':
 * @property integer $id
 * @property integer $parent_item_id
 * @property integer $menu_id
 * @property string $item_name
 * @property string $item_type
 * @property string $value
 * @property integer $position
 * @property string $link_options
 * @property string $item_options
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Menu $menu
 * @property MenuItem $parentItem
 * @property MenuItem[] $menuItems
 * --- END ModelDoc ---
 */
class MenuItem extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MenuItem the static model class
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
        return '{{menu_item}}';
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

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item_name, item_type', 'required', 'on' => 'adminInsert, adminUpdate'),
            array('parent_item_id', 'default', 'value' => null, 'on' => 'adminInsert, adminUpdate'),
            array('parent_item_id', 'numerical', 'integerOnly' => true, 'on' => 'adminInsert, adminUpdate'),
            array('parent_item_id', 'in', 'skipOnError' => true, 'range' => array_keys($this->getAllowedParentsForList()), 'on' => 'adminInsert, adminUpdate'),
            array(
                implode(', ', array(
                    'item_name',
                )),
                'length',
                'min'  =>  Yii::app()->params['admin']['min-string-field-size'],
                'max'  =>  Yii::app()->params['admin']['max-string-field-size'],
                'on' => 'adminInsert, adminUpdate',
            ),
            array('position', 'ifIsEmptyPosition', 'on' => 'adminInsert'),
            array('position', 'numerical', 'allowEmpty' => false, 'integerOnly' => true, 'on' => 'adminInsert, adminUpdate, changePosition'),
            array(
                implode(', ', array(
                    'link_options',
                    'item_options',
                )),
                'length',
                'max'  =>  Yii::app()->params['admin']['max-text-field-size'],
                'on' => 'adminInsert, adminUpdate',
            ),
            array('item_type', 'in', 'range' => array_keys(Yii::app()->menuItemHelper->types), 'on' => 'adminInsert, adminUpdate'),
            array('value', 'validateValue', 'on' => 'adminInsert, adminUpdate'),
            array(
                implode(', ', array(
                    'item_name',
                    'created_at',
                )),
                'length',
                'on'            =>  'search',
            ),
            array(
                implode(', ', array(
                    'id',
                    'position',
                    'parent_item_id',
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
            'parentItem'   =>  array(self::BELONGS_TO, 'MenuItem', 'parent_item_id'),
            'items'    =>  array(self::HAS_MANY, 'MenuItem', 'parent_item_id'),
            'menu'         =>  array(self::BELONGS_TO, 'Menu', 'menu_id'),
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array_map(function ($label) {
            return Yii::t('admin', Yii::t('menuItem', $label));
        }, array(
            'id'            =>  'ID',
            'parent_item_id'    =>  'Parent Item',
            'menu_id'       =>  'Menu',
            'item_name'     =>  'Item Name',
            'item_type'     =>  'Item Type',
            'value'         =>  'Value',
            'position'      =>  'Position',
            'link_options'  =>  'Link Options',
            'item_options'  =>  'Item Options',
            'created_at'    =>  'Created At',
            'updated_at'    =>  'Updated At',
        ));
    }

    public function validateValue()
    {
        return Yii::app()->menuItemHelper->types[$this->item_type]->validate($this);
    }

    /**
     * Apply search conditions
     * @return MenuItem     */
    public function search()
    {
        $criteria = new DbCriteria;
        $this->validate();

        // Note: please modify the following code to remove attributes that should not be searched.

        if (!$this->hasErrors('id')) {
            $criteria->compare('id', $this->id);
        }
        if (!$this->hasErrors('parent_item_id')) {
            $criteria->compare('parent_item_id', $this->parent_item_id);
        }
        if (!$this->hasErrors('item_name')) {
            $criteria->compare('item_name', $this->item_name, true);
        }
        if (!$this->hasErrors('position')) {
            $criteria->compare('position', $this->position);
        }
        if (!$this->hasErrors('created_at')) {
            $criteria->compare('created_at', $this->created_at, true);
        }

        $this->dbCriteria->mergeWith($criteria);
        return $this;
    }

    /**
     * Get all items that allowed to set as parentItem for current item.
     * @return MenuItem[] array in id => MenuItem format.
     */
    public function getAllowedParentsForList()
    {
        $c = new DbCriteria(array('index' => 'id'));
        $items = MenuItem::model()->findAll($c);
        return array_diff_key($items, $this->getAllChildrenForItem());
    }

    /**
     * Get current item and all it's child items recursively.
     * @return MenuItem[] array in id => MenuItem format.
     */
    public function getAllChildrenForItem()
    {
        if($this->isNewRecord){
            return array();
        }
        $children[$this->id] = $this;
        foreach($this->items as $child){
            $children += $child->getAllChildrenForItem();
        }
        return $children;
    }

    /**
     * Scope for getting higher items.
     * @return MenuItem
     */
    public function higherItems($menuId)
    {
        $c1 = new DbCriteria(array('index' => 'id'));
        $c1->with = array(
            'items' =>  array(
                'together'  =>  true,
            ),
        );
        $c1->addNotInCondition('items.id', array(null), 'OR');
        $c1->addInCondition($this->tableAlias.'.parent_item_id', array(null), 'OR');

        $c2 = new DbCriteria();
        $c2->mergeWith($c1);
        $c2->addColumnCondition(array($this->tableAlias.'.menu_id' => $menuId));

        $this->getDbCriteria()->mergeWith($c2);
        return $this;
    }

    /**
     * Scope for getting items for particular menu
     * @param integer $menu_id
     * @return $this
     */
    public function getItemsForMenu($menu_id)
    {
        $criteria = new DbCriteria();
        $criteria->with = 'menu';
        $criteria->params = array(
            'menu_id' => $menu_id,
        );
        $criteria->addCondition($this->tableAlias.'.menu_id = :menu_id');
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * @return string item name
     */
    public function __toString()
    {
        return $this->item_name;
    }

    public function ifIsEmptyPosition()
    {
        if($this->position === null || $this->position === ''){
            $this->position = (int) $this->commandBuilder->createFindCommand($this->tableName(), new DbCriteria(array(
                'select'    =>  'MAX(position)',
            )))->queryScalar() + 1;
        }
    }

    public function getHtmlOptions($option)
    {
        return parse_ini_string($option);
    }
}
