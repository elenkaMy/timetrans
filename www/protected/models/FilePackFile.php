<?php

/**
 * --- BEGIN ModelDoc ---
 *
 * This is the model class for table "file_pack_file".
 *
 * The followings are the available columns in table 'file_pack_file':
 * @property integer $id
 * @property integer $file_id
 * @property integer $pack_file_id
 * @property integer $position
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property PackFile $packFile
 * @property File $file
 *
 * The followings are the available basic CActiveRecord methods:
 * @see CActiveRecord
 * @method FilePackFile find() find($condition = '', array $params = array())
 * @method FilePackFile findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method FilePackFile findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method FilePackFile findBySql() findBySql($sql, array $params = array())
 * @method FilePackFile[] findAll() findAll($condition = '', array $params = array())
 * @method FilePackFile[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method FilePackFile[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method FilePackFile[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method FilePackFile with() with()
 *
 * --- END ModelDoc ---
 * 
 * @property-read integer $nextPosition
 */
class FilePackFile extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return FilePackFile the static model class
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
        return '{{file_pack_file}}';
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
            'packFile'  =>  array(self::BELONGS_TO, 'PackFile', 'pack_file_id'),
            'file'      =>  array(self::BELONGS_TO, 'File', 'file_id'),
        );
    }

    /**
     * @return array customized attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array(
            'id'            =>  'ID',
            'file_id'       =>  'File',
            'pack_file_id'  =>  'Pack File',
            'position'      =>  'Position',
            'created_at'    =>  'Created At',
            'updated_at'    =>  'Updated At',
        );
    }

    /**
     * @param PackFile|int|null $package package or package_id.
     * @return integer
     */
    public function getNextPosition($package = null)
    {
        $criteria = new DbCriteria;
        $criteria->select = 'MAX(position)';

        if (!is_null($package)) {
            if ($package instanceof PackFile) {
                $package = $package->id;
            }
            $criteria->addColumnCondition(array(
                'pack_file_id'  =>  $package,
            ));
        }

        $position = (int) $this->commandBuilder
            ->createFindCommand($this->tableName(), $criteria)
            ->queryScalar();
        return $position + 1;
    }
}
