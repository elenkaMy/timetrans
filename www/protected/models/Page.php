<?php

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "page".
 *
 * The followings are the available columns in table 'page':
 * @property integer $id
 * @property integer $parent_page_id
 * @property string $page_name
 * @property string $fixed_name
 * @property string $alias
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
 * @property Page $parentPage
 * @property Page[] $pages
 *
 * The followings are the available basic CActiveRecord methods:
 * @see CActiveRecord
 * @method Page find() find($condition = '', array $params = array())
 * @method Page findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method Page findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Page findBySql() findBySql($sql, array $params = array())
 * @method Page[] findAll() findAll($condition = '', array $params = array())
 * @method Page[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method Page[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Page[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method Page with() with()
 *
 * The followings are the available model scopes:
 * @method Page position()
 *
 * --- END ModelDoc ---
 */
class Page extends ActiveRecord
{
    static private $_fixedData = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Page the static model class
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
        return '{{page}}';
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
     * @param string $name
     * @return Page
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

    protected function beforeDelete()
    {
        if($this->fixed_name !== null){
            return false;
        }
        return parent::beforeDelete();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('page_name, alias', 'required', 'on' => 'adminInsert, adminUpdate'),
            array('parent_page_id', 'default', 'value' => null, 'on' => 'adminInsert, adminUpdate'),
            array('parent_page_id', 'numerical', 'integerOnly' => true, 'on' => 'adminInsert, adminUpdate'),
            array('parent_page_id', 'in', 'skipOnError' => true, 'range' => array_keys($this->getAllowedParentsForList()), 'on' => 'adminInsert, adminUpdate'),
            array(
                implode(', ', array(
                    'page_name',
                    'alias',
                    'seo_title',
                )),
                'length',
                'min' => Yii::app()->params['admin']['min-string-field-size'],
                'max' => Yii::app()->params['admin']['max-string-field-size'],
                'on'  => 'adminInsert, adminUpdate',
            ),
            array('alias', 'UniqueValidator', 'multipleColumns' => 'parent_page_id', 'skipOnError' => true, 'on' => 'adminInsert, adminUpdate'),
            array('alias', 'AliasValidator', 'skipOnError' => true, 'allowEmpty' => true, 'on' => 'adminInsert, adminUpdate'),
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
            array('position', 'ifIsEmptyPosition', 'on' => 'adminInsert'),
            array('position', 'numerical', 'allowEmpty' => false, 'integerOnly' => true, 'on' => 'adminInsert, adminUpdate, changePosition'),
            // The following rule is used by search().
            // NOTE: Please remove those attributes that should not be searched.
            array(
                implode(', ', array(
                    'page_name',
                    'alias',
                    'position',
                    'created_at',
                )),
                'length',
                'on'            =>  'search',
            ),
            array(
                implode(', ', array(
                    'id',
                    'parent_page_id',
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
            'parentPage'    =>  array(self::BELONGS_TO, 'Page', 'parent_page_id'),
            'pages'         =>  array(
                self::HAS_MANY,
                'Page',
                'parent_page_id',
                'order' => 'pages.position',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array_map(function ($label) {
            return Yii::t('admin', Yii::t('page', $label));
        }, array(
            'id'                =>  'ID',
            'parent_page_id'    =>  'Parent Page',
            'page_name'         =>  'Page Name',
            'fixed_name'        =>  'Fixed Name',
            'alias'             =>  'Alias',
            'content'           =>  'Content',
            'short_content'     =>  'Short Content',
            'seo_title'         =>  'Seo Title',
            'seo_description'   =>  'Seo Description',
            'seo_keywords'      =>  'Seo Keywords',
            'position'          =>  'Position',
            'created_at'        =>  'Created At',
            'updated_at'        =>  'Updated At',
        ));
    }

    /**
     * Apply search conditions
     * @return Page     */
    public function search()
    {
        $criteria = new DbCriteria;
        $this->validate();

        // Note: please modify the following code to remove attributes that should not be searched.
        if (!$this->hasErrors('id')) {
            $criteria->compare('id', $this->id);
        }
        if (!$this->hasErrors('parent_page_id')) {
            $criteria->compare('parent_page_id', $this->parent_page_id);
        }
        if (!$this->hasErrors('page_name')) {
            $criteria->compare('page_name', $this->page_name, true);
        }
        if (!$this->hasErrors('alias')) {
            $criteria->compare('alias', $this->alias, true);
        }
        if (!$this->hasErrors('position')) {
            $criteria->compare('position', $this->position, true);
        }
        if (!$this->hasErrors('created_at')) {
            $criteria->compare('created_at', $this->created_at, true);
        }
        $this->dbCriteria->mergeWith($criteria);
        return $this;
    }

    /**
     * Get all pages that allowed to set as parentPage for current page.
     * @return Page[] array in id => Page format.
     */
    public function getAllowedParentsForList()
    {
        $c = new DbCriteria(array('index' => 'id'));
        $pages = Page::model()->findAll($c);
        return array_diff_key($pages, $this->getAllChildrenForPage());
    }

    /**
     * Get current page and all it's child pages recursively.
     * @return Page[] array in id => Page format.
     */
    public function getAllChildrenForPage()
    {
        if($this->isNewRecord){
            return array();
        }
        $children[$this->id] = $this;
        foreach($this->pages as $child){
            $children += $child->getAllChildrenForPage();
        }
        return $children;
    }

    /**
     * Scope for getting higher pages.
     * @return Page
     */
    public function higherPages()
    {
        $c = new DbCriteria(array('index' => 'id'));
        $c->with = array(
            'pages' =>  array(
                'together'  =>  true,
            ),
        );
        $c->condition = "pages.id is NOT NULL OR $this->tableAlias.parent_page_id IS NULL";
        $this->getDbCriteria()->mergeWith($c);
        return $this;
    }

    /**
     * @return Page[]
     */
    public function getAllParents()
    {
        if ($this->parent_page_id) {
            return array_merge(array($this->parentPage), $this->parentPage->allParents);
        } else {
            return array();
        }
    }

    /**
     * @return string page name
     */
    public function __toString()
    {
        return $this->page_name;
    }

    public function scopes()
    {
        return array(
            'position'  =>  array(
                'order'  =>  'position',
            ),
        );
    }

    public function ifIsEmptyPosition()
    {
        if($this->position === null || $this->position === ''){
            $this->position = (int) $this->commandBuilder->createFindCommand($this->tableName(), new DbCriteria(array(
                'select'    =>  'MAX(position)',
            )))->queryScalar() + 1;
        }
    }

    public function getPagesForList()
    {
        $c=new DbCriteria(array('index' => 'id'));
        return Page::model()->findAll($c);
    }
}
