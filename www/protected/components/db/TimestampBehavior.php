<?php

Yii::import('zii.behaviors.CTimestampBehavior');

class TimestampBehavior extends CTimestampBehavior
{
    public $createAttribute = 'created_at_timestamp';
    public $updateAttribute = 'updated_at_timestamp';

    public $createDatetimeAttribute = 'created_at';
    public $updateDatetimeAttribute = 'updated_at';

    public $setUpdateOnCreate = true;

    public $timestampExpression = '($t = @explode(" ", microtime())) ? $t[1].".".($t[0]*1000000) : microtime(true)';

    public $dbFromUnixtimeFunction = 'FROM_UNIXTIME';

    public function beforeSave($event) {
        parent::beforeSave($event);

        if ($this->getOwner()->getIsNewRecord() && ($this->createDatetimeAttribute !== null)) {
            $this->getOwner()->{$this->createDatetimeAttribute} = $this->getDatetimeByAttribute($this->createDatetimeAttribute, $this->createAttribute);
        }
        if ((!$this->getOwner()->getIsNewRecord() || $this->setUpdateOnCreate) && ($this->updateDatetimeAttribute !== null)) {
            $this->getOwner()->{$this->updateDatetimeAttribute} = $this->getDatetimeByAttribute($this->updateDatetimeAttribute, $this->updateAttribute);
        }
        if ($this->getOwner()->getIsNewRecord() && $this->setUpdateOnCreate) {
            if ($this->createAttribute !== null && $this->updateAttribute !== null) {
                $this->getOwner()->{$this->updateAttribute} = $this->getOwner()->{$this->createAttribute};
            }
            if ($this->createDatetimeAttribute !== null && $this->updateDatetimeAttribute !== null) {
                $this->getOwner()->{$this->updateDatetimeAttribute} = $this->getOwner()->{$this->createDatetimeAttribute};
            }
        }
    }

    /**
     * Gets the appropriate datetime depending on the column type $attribute is.
     * 
     * @param string $attribute
     * @param string $milisecondAttribute
     * @return mixed timestamp (eg unix timestamp or a mysql function, without milisecond)
     */
    public function getDatetimeByAttribute($attribute, $milisecondAttribute = null)
    {
        return ($milisecondAttribute !== null)
            ? new CDbExpression("{$this->dbFromUnixtimeFunction}(:$attribute)", array($attribute => $this->getOwner()->{$milisecondAttribute}))
            : $this->getTimestampByAttribute($attribute);
    }

    /**
     * Up function access level to public
     * @param string $attribute
     * @return mixed
     */
    public function getTimestampByAttribute($attribute) {
        return parent::getTimestampByAttribute($attribute);
    }
}
