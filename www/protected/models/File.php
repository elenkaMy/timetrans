<?php

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "file".
 *
 * The followings are the available columns in table 'file':
 * @property integer $id
 * @property string $file
 * @property string $path
 * @property string $mime_type
 * @property integer $size
 * @property string $ext
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property FilePackFile[] $filePackFiles
 * @property PackFile[] $packFiles
 *
 * The followings are the available basic CActiveRecord methods:
 * @see CActiveRecord
 * @method File find() find($condition = '', array $params = array())
 * @method File findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method File findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method File findBySql() findBySql($sql, array $params = array())
 * @method File[] findAll() findAll($condition = '', array $params = array())
 * @method File[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method File[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method File[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method File with() with()
 *
 * --- END ModelDoc ---
 */
class File extends ActiveRecord
{
    public $fileValidator = null;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return File the static model class
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
        return '{{file}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    public function normalizePath($path)
    {
        if (is_string($this->path)) {
            return str_replace('\\', '/', $this->path);
        }
        return $path;
    }

    protected function afterFind()
    {
        $this->path = $this->normalizePath($this->path);

        return parent::afterFind();
    }

    protected function beforeSave()
    {
        $this->path = $this->normalizePath($this->path);

        return parent::beforeSave();
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
     * @throws CException if validator is not defined
     */
    public function rules()
    {
        if (is_null($this->fileValidator) && $this->scenario === 'upload') {
            throw new CException('Validator must be defined');
        }
        return array_merge(
            is_null($this->fileValidator)
                ? array() 
                : array(
                    array_merge(array('file'), $this->fileValidator, array('on' => 'upload'))
        ), array(
            array(
                'file',
                'length',
                'skipOnError'   =>  true,
                'allowEmpty'    =>  false,
                'min'           =>  1,
                'max'           =>  255,
                'safe'          =>  false,
                'on'            =>  array(
                    'upload',
                ),
            ),
            array(
                'file',
                'match',
                'skipOnError'   =>  true,
                'allowEmpty'    =>  false,
                'pattern'       =>  '/^[^\/\?\*\:\;\{\}\\\[\]]+\.[^\/\?\*\:\;\{\}\\\[\]]+$/',
                'safe'          =>  false,
                'on'            =>  array(
                    'upload',
                ),
            ),
            array(
                'file',
                'match',
                'skipOnError'   =>  true,
                'allowEmpty'    =>  false,
                'not'           =>  true,
                'pattern'       =>  '/\.(htaccess|git.*)$/i',
                'safe'          =>  false,
                'on'            =>  array(
                    'upload',
                ),
            ),

            array(
                array(
                    'file',
                    'path',
                    'mime_type',
                    'size',
                    'ext',
                ),
                'required',
                'on'        =>  array(
                    'insertFileInDbFileBehavior',
                ),
            ),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'filePackFiles' =>  array(self::HAS_MANY, 'FilePackFile', 'file_id'),
            'packFiles'     =>  array(
                self::HAS_MANY,
                'PackFile',
                array('pack_file_id' => 'id'),
                'through'   =>  'filePackFiles',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array_map(function ($label) {
            return Yii::t('file', $label);
        }, array(
            'id'            =>  'ID',
            'file'          =>  'File',
            'pack_file_id'  =>  'Pack File',
            'path'          =>  'Path',
            'mime_type'     =>  'Mime Type',
            'size'          =>  'Size',
            'ext'           =>  'Ext',
            'created_at'    =>  'Created At',
            'updated_at'    =>  'Updated At',
        ));
    }
}
