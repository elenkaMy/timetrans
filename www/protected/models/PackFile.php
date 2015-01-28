<?php

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "pack_file".
 *
 * The followings are the available columns in table 'pack_file':
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property FilePackFile[] $filePackFiles
 * @property File[] $files
 *
 * The followings are the available basic CActiveRecord methods:
 * @see CActiveRecord
 * @method PackFile find() find($condition = '', array $params = array())
 * @method PackFile findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method PackFile findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method PackFile findBySql() findBySql($sql, array $params = array())
 * @method PackFile[] findAll() findAll($condition = '', array $params = array())
 * @method PackFile[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method PackFile[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method PackFile[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method PackFile with() with()
 *
 * --- END ModelDoc ---
 */
class PackFile extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PackFile the static model class
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
        return '{{pack_file}}';
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
        return array(
            array('position', 'numerical', 'integerOnly' => true, 'on' => 'upload'),
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
            'filePackFiles' =>  array(self::HAS_MANY, 'FilePackFile', 'pack_file_id'),
            'files'         =>  array(
                self::HAS_MANY,
                'File',
                array('file_id' => 'id'),
                'through'   =>  'filePackFiles',
                'order'     =>  'filePackFiles.position',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array(
            'id'            =>  'ID',
            'created_at'    =>  'Created At',
            'updated_at'    =>  'Updated At',
        );
    }
}
