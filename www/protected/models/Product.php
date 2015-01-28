<?php

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property integer $category_id
 * @property integer $file_id
 * @property string $product_name
 * @property string $alias
 * @property string $content
 * @property string $short_content
 * @property double $price
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property integer $position
 * @property string $created_at
 * @property string $updated_at
 * @property integer $pack_file_id
 * @property bool $visible
 * @property string $article
 *
 * The followings are the available model relations:
 * @property File $file
 * @property PackFile $packFile
 * @property ProductCategory $category
 *
 * The followings are the available basic CActiveRecord methods:
 * @see CActiveRecord
 * @method Product find() find($condition = '', array $params = array())
 * @method Product findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method Product findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Product findBySql() findBySql($sql, array $params = array())
 * @method Product[] findAll() findAll($condition = '', array $params = array())
 * @method Product[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method Product[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Product[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method Product with() with()
 *
 * --- END ModelDoc ---
 */
class Product extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Product the static model class
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
        return '{{product}}';
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
            'DbFileBehavior'    =>  array(
                'class' =>  'application.components.db.behaviors.DbFileBehavior',
                'columns'    =>  array(
                    'file_id'   =>  Yii::app()->fileHelper->contexts['product'],
                    'pack_file_id'  =>  Yii::app()->fileHelper->contexts['product'],
                ),
                'multipleColumns' => array('pack_file_id')
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
            array('category_id, product_name, alias, visible', 'required', 'on' => 'adminInsert, adminUpdate'),
            array('visible', 'boolean', 'on' => 'adminInsert, adminUpdate'),
            array('pack_file_id', 'FilesCountValidator', 'min' => 1, 'max' => 10, 'on' => 'adminInsert, adminUpdate'),
            array(
                implode(', ', array(
                    'product_name',
                    'alias',
                )),
                'length',
                'min' => Yii::app()->params['admin']['min-string-field-size'],
                'max' => Yii::app()->params['admin']['max-string-field-size'],
                'on'  => 'adminInsert, adminUpdate',
            ),
            array('article', 'length', 'max' => 255, 'on' => 'adminInsert, adminUpdate'),
            array(
                'seo_title',
                'length',
                'max' => Yii::app()->params['admin']['max-string-field-size'],
                'on'  => 'adminInsert, adminUpdate',
            ),

            array('category_id', 'numerical', 'integerOnly' => true, 'on' => 'adminInsert, adminUpdate'),
            array('category_id', 'exist', 'className' => 'ProductCategory', 'attributeName' => 'id', 'skipOnError' => true, 'on' => 'adminInsert, adminUpdate'),

            array('alias', 'UniqueValidator', 'multipleColumns' => 'category_id', 'skipOnError' => true, 'on' => 'adminInsert, adminUpdate'),
            array('alias', 'sameCategoryNotExists', 'skipOnError' => true, 'on' => 'adminInsert, adminUpdate'),
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
            array('position', 'numerical', 'integerOnly' => true, 'on' => 'adminInsert, adminUpdate'),
            array('position', 'ifIsEmptyPosition', 'integerOnly' => true, 'on' => 'adminInsert, adminUpdate'),
            array('price', 'numerical', 'on' => 'adminInsert, adminUpdate'),
            array('file_id', 'FileRequiredValidator', 'on' => 'adminInsert, adminUpdate'),
            array(
                implode(', ', array(
                    'product_name',
                    'price',
                    'created_at',
                    'visible',
                    'article',
                )),
                'length',
                'on'            =>  'search',
            ),
            array(
                implode(', ', array(
                    'id',
                    'category_id',
                    'position',
                )),
                'numerical',
                'integerOnly'   =>  true,
                'on'            =>  'search',
            ),

        );
    }

    public function sameCategoryNotExists($attribute, $params)
    {
        if ($this->hasErrors($attribute) || $this->hasErrors('category_id')) {
            return;
        }

        $columns  = array(
            'alias'                 =>  $this->{$attribute},
            'parent_category_id'    =>  $this->category_id,
        );
        if (ProductCategory::model()->existsByAttributes($columns)) {
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
            'file'          =>  array(self::BELONGS_TO, 'File', 'file_id'),
            'category'      =>  array(self::BELONGS_TO, 'ProductCategory', 'category_id'),
            'packFile'      =>  array(
                self::BELONGS_TO,
                'PackFile',
                'pack_file_id'
            ),
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array_map(function ($label) {
            return Yii::t('admin', Yii::t('product', $label));
        }, array(
            'id'                =>  'ID',
            'category_id'       =>  'Category',
            'file_id'           =>  'File',
            'product_name'      =>  'Product Name',
            'alias'             =>  'Alias',
            'content'           =>  'Content',
            'short_content'     =>  'Short Content',
            'price'             =>  'Price',
            'seo_title'         =>  'Seo Title',
            'seo_description'   =>  'Seo Description',
            'seo_keywords'      =>  'Seo Keywords',
            'position'          =>  'Position',
            'created_at'        =>  'Created At',
            'updated_at'        =>  'Updated At',
            'visible'           =>  'Visible',
            'pack_file_id'      =>  'Pack File',
            'article'           =>  'Article',
        ));
    }

    /**
     * Apply search conditions
     * @return Product
     */
    public function search()
    {
        $criteria = new DbCriteria;
        $this->validate();

        // Note: please modify the following code to remove attributes that should not be searched.
        if (!$this->hasErrors('id')) {
            $criteria->compare("$this->tableAlias.id", $this->id);
        }
        if (!$this->hasErrors('category_id')) {
            $criteria->compare("$this->tableAlias.category_id", $this->category_id);
        }
        if (!$this->hasErrors('product_name')) {
            $criteria->compare("$this->tableAlias.product_name", $this->product_name, true);
        }
        if (!$this->hasErrors('price')) {
            $criteria->compare("$this->tableAlias.price", $this->price);
        }
        if (!$this->hasErrors('position')) {
            $criteria->compare("$this->tableAlias.position", $this->position);
        }
        if (!$this->hasErrors('created_at')) {
            $criteria->compare("$this->tableAlias.created_at", $this->created_at, true);
        }
        if (!$this->hasErrors('visible')) {
            $criteria->compare("$this->tableAlias.visible", $this->visible);
        }
        if (!$this->hasErrors('article')) {
            $criteria->compare("$this->tableAlias.article", $this->product_name, true);
        }

        $this->dbCriteria->mergeWith($criteria);
        return $this;
    }

    /**
     * Scope for getting products for particular category
     * @param integer|ProductCategory $category_id
     * @return $this
     */
    public function getProductsByCategory($category_id)
    {
        if ($category_id instanceof ProductCategory) {
            $category_id = $category_id->id;
        }

        $criteria = new DbCriteria();
        $criteria->with = 'category';
        $criteria->params = array(
            'category_id' => $category_id,
        );
        $criteria->addCondition($this->tableAlias.'.category_id = :category_id');
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public function ifIsEmptyPosition()
    {
        if ($this->position === null || $this->position === ''){
            $this->position = (int) $this->commandBuilder->createFindCommand($this->tableName(), new DbCriteria(array(
                'select'    =>  'MAX(position)',
            )))->queryScalar() + 1;
        }
    }

    public function __toString()
    {
        return $this->product_name;
    }
}
