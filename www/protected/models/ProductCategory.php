<?php

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "product_category".
 *
 * The followings are the available columns in table 'product_category':
 * @property integer $id
 * @property integer $parent_category_id
 * @property integer $file_id
 * @property string $category_name
 * @property string $alias
 * @property string $fixed_name
 * @property string $content
 * @property string $short_content
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property integer $position
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Product[] $products
 * @property File $file
 * @property ProductCategory $parentCategory
 * @property ProductCategory[] $productCategories
 *
 * The followings are the available basic CActiveRecord methods:
 * @see CActiveRecord
 * @method ProductCategory find() find($condition = '', array $params = array())
 * @method ProductCategory findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method ProductCategory findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method ProductCategory findBySql() findBySql($sql, array $params = array())
 * @method ProductCategory[] findAll() findAll($condition = '', array $params = array())
 * @method ProductCategory[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method ProductCategory[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method ProductCategory[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method ProductCategory with() with()
 *
 * --- END ModelDoc ---
 * 
 * @property-read ProductCategory[] $allowedParentsForList in id => ProductCategory format.
 * @property-read ProductCategory[] $allChildrenForCategory in id => ProductCategory format.
 * @property-read ProductCategory[] $allParents
 * @property-read ProductCategory[] $allCategories in id => ProductCategory format.
 * 
 */
class ProductCategory extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductCategory the static model class
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
        return '{{product_category}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    protected function beforeDelete()
    {
        if ($this->fixed_name !== null){
            return false;
        }
        return parent::beforeDelete();
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
            'DbFileBehavior'    =>  array(
                'class' =>  'application.components.db.behaviors.DbFileBehavior',
                'columns'    =>  array(
                    'file_id'   =>  Yii::app()->fileHelper->contexts['product'],
                ),
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
            array('category_name, alias', 'required', 'on' => 'adminInsert, adminUpdate'),
            array(
                implode(', ', array(
                    'category_name',
                    'alias',
                )),
                'length',
                'min' => Yii::app()->params['admin']['min-string-field-size'],
                'max' => Yii::app()->params['admin']['max-string-field-size'],
                'on'  => 'adminInsert, adminUpdate',
            ),
            array(
                'seo_title',
                'length',
                'max' => Yii::app()->params['admin']['max-string-field-size'],
                'on'  => 'adminInsert, adminUpdate',
            ),

//            array('parent_category_id', 'default', 'value' => null, 'on' => 'adminInsert, adminUpdate'),
//            array('parent_category_id', 'numerical', 'integerOnly' => true, 'on' => 'adminInsert, adminUpdate'),
//            array('parent_category_id', 'in', 'skipOnError' => true, 'range' => array_keys($this->getAllowedParentsForList()), 'on' => 'adminInsert, adminUpdate'),

//            array('alias', 'UniqueValidator', 'multipleColumns' => 'parent_category_id', 'skipOnError' => true, 'on' => 'adminInsert, adminUpdate'),
            array('alias', 'sameProductNotExists', 'skipOnError' => true, 'on' => 'adminInsert, adminUpdate'),
            array('alias', 'AliasValidator', 'skipOnError' => true, 'on' => 'adminInsert, adminUpdate'),

            array(
                implode(', ', array(
                    'content',
                    'short_content',
                    'seo_description',
                    'seo_keywords',
                )),
                'length',
                'max' => Yii::app()->params['admin']['max-text-field-size'],
                'on'  => 'adminInsert, adminUpdate',
            ),
            array('position', 'ifIsEmptyPosition', 'on' => 'adminInsert, adminUpdate'),
            array('position', 'numerical', 'integerOnly' => true, 'on' => 'adminInsert, adminUpdate'),
            array('file_id', 'FileRequiredValidator', 'on' => 'adminInsert, adminUpdate'),
            array(
                implode(', ', array(
                    'category_name',
                    'created_at',
                )),
                'length',
                'on'            =>  'search',
            ),
            array(
                implode(', ', array(
                    'id',
//                    'parent_category_id',
                    'position',
                )),
                'numerical',
                'integerOnly'   =>  true,
                'on'            =>  'search',
            ),

        );
    }

    public function sameProductNotExists($attribute, $params)
    {
        if ($this->hasErrors($attribute) || $this->hasErrors('parent_category_id') || !$this->parent_category_id) {
            return;
        }

        $columns  = array(
            'alias'         =>  $this->{$attribute},
            'category_id'   =>  $this->parent_category_id,
        );
        if (Product::model()->existsByAttributes($columns)) {
            $this->addError($attribute, Yii::t('yii', '{attribute} "{value}" has already been taken.', array(
                '{attribute}'   =>  $this->getAttributeLabel($attribute),
                '{value}'       =>  $this->{$attribute},
            )));
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'products'          =>  array(
                self::HAS_MANY,
                'Product',
                'category_id',
                'order' => 'products.position',
            ),
            'file'              =>  array(self::BELONGS_TO, 'File', 'file_id'),
            'parentCategory'    =>  array(self::BELONGS_TO, 'ProductCategory', 'parent_category_id'),
            'productCategories' =>  array(
                self::HAS_MANY,
                'ProductCategory',
                'parent_category_id',
                'order' => 'productCategories.position',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array_map(function ($label) {
            return Yii::t('admin', Yii::t('productCategory', $label));
        }, array(
            'id'                    =>  'ID',
            'parent_category_id'    =>  'Parent Category',
            'file_id'               =>  'File',
            'category_name'         =>  'Category Name',
            'alias'                 =>  'Alias',
            'fixed_name'            =>  'Fixed Name',
            'content'               =>  'Content',
            'short_content'         =>  'Short Content',
            'seo_title'             =>  'Seo Title',
            'seo_description'       =>  'Seo Description',
            'seo_keywords'          =>  'Seo Keywords',
            'position'              =>  'Position',
            'created_at'            =>  'Created At',
            'updated_at'            =>  'Updated At',
        ));
    }

    /**
     * Apply search conditions
     * @return ProductCategory
     */
    public function search()
    {
        $criteria = new DbCriteria;
        $this->validate();

        // Note: please modify the following code to remove attributes that should not be searched.
        if (!$this->hasErrors('id')) {
            $criteria->compare("$this->tableAlias.id", $this->id);
        }
//        if (!$this->hasErrors('parent_category_id')) {
//            $criteria->compare("$this->tableAlias.parent_category_id", $this->parent_category_id);
//        }
        if (!$this->hasErrors('category_name')) {
            $criteria->compare("$this->tableAlias.category_name", $this->category_name, true);
        }
        if (!$this->hasErrors('position')) {
            $criteria->compare("$this->tableAlias.position", $this->position);
        }
        if (!$this->hasErrors('created_at')) {
            $criteria->compare("$this->tableAlias.created_at", $this->created_at, true);
        }

        $this->dbCriteria->mergeWith($criteria);
        return $this;
    }

    /**
     * Get all product categories that allowed to set as parent category for current product category.
     * @return ProductCategory[] array in id => ProductCategory format.
     */
    public function getAllowedParentsForList()
    {
        $c = new DbCriteria(array('index' => 'id'));
        $pages = ProductCategory::model()->findAll($c);
        return array_diff_key($pages, $this->getAllChildrenForCategory());
    }

    /**
     * Get current product category and all it's child categories recursively.
     * @return ProductCategory[] array in id => ProductCategory format.
     */
    public function getAllChildrenForCategory()
    {
        if($this->isNewRecord){
            return array();
        }
        $children[$this->id] = $this;
        foreach($this->productCategories as $child){
            $children += $child->getAllChildrenForCategory();
        }
        return $children;
    }

    /**
     * Scope for getting higher product categories.
     * @return ProductCategory
     */
    public function higherCategories()
    {
        $c = new DbCriteria(array('index' => 'id'));
        $c->with = array(
            'productCategories' =>  array(
                'together'  =>  true,
            ),
        );
        $c->condition = "productCategories.id is NOT NULL OR $this->tableAlias.parent_category_id IS NULL";
        $this->getDbCriteria()->mergeWith($c);
        return $this;
    }

    /**
     * @return ProductCategory[]
     */
    public function getAllParents()
    {
        if ($this->parent_category_id) {
            return array_merge(array($this->parentCategory), $this->parentCategory->allParents);
        } else {
            return array();
        }
    }

    /**
     * @return string product category name
     */
    public function __toString()
    {
        return $this->category_name;
    }

    public function scopes()
    {
        return array(
            'position'  =>  array(
                'order'  =>  $this->tableAlias.'.position',
            ),
        );
    }

    public function ifIsEmptyPosition()
    {
        if ($this->position === null || $this->position === ''){
            $this->position = (int) $this->commandBuilder->createFindCommand($this->tableName(), new DbCriteria(array(
                'select'    =>  'MAX(position)',
            )))->queryScalar() + 1;
        }
    }

    public function getAllCategories()
    {
        $criteria = new DbCriteria(array('index' => 'id'));
        return self::model()->findAll($criteria);
    }
}
