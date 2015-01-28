<?php

/**
 * @property-read CActiveRecord $model
 * @property-read CActiveRecord[] $dataForList
 * @property-read string $modelClass
 * @property-read string $route
 */
abstract class AbstractARMenuItemType extends AbstractMenuItemType
{
    /**
     * @return string
     */
    public function getModelClass()
    {
        return ucfirst($this->shortTypeName);
    }

    /**
     * @return string
     */
    abstract public function getRoute();

    /**
     * @return CActiveRecord
     */
    public function getModel()
    {
        if (!is_a($this->modelClass, 'CActiveRecord', true)) {
            throw new CException('AbstractARMenuItemType model class must be an instance of CActiveRecord');
        }
        $result = call_user_func(array($this->modelClass, 'model'));

        $reflectionClass = new ReflectionClass($result);
        if ($reflectionClass->isAbstract()) {
            throw new CException('AbstractARMenuItemType cannot work with abstract models.');
        }
        switch (false) {
            case $reflectionClass->hasMethod('__toString'):
            case $reflectionClass->getMethod('__toString')->isPublic():
            case !$reflectionClass->getMethod('__toString')->isStatic():
            case !$reflectionClass->getMethod('__toString')->isAbstract():
                throw new CException('AbstractARMenuItemType model class must have __toString method.');
        }

        return $result;
    }

    /**
     * @return CActiveRecord[]
     */
    public function getDataForList()
    {
        return $this->model->findAll(new DbCriteria(array(
            'index' =>  $this->model->primaryKey(),
        )));
    }

    public function renderFormElement(MenuItem $item)
    {
        $widget = new CWidget();
        return $widget->widget('application.components.menu-items.widgets.ArDropDownMenuItemTypeWidget', array(
            'menuItem'      =>  $item,
            'menuItemType'  =>  $this,
        ), true);
    }

    public function getUrl(MenuItem $item, $asRouteIfPossible = false)
    {
        $route = ltrim($this->route, '/');
        return $asRouteIfPossible
            ? array("/$route", 'id' => $item->value)
            : Yii::app()->createUrl($route, array('id' => $item->value));
    }

    /**
     * @return AbstractARMenuItemType
     */
    public function clearMenuItems()
    {
        $criteria = new DbCriteria();
        $criteria->distinct = true;
        $criteria->select = 'value';
        $criteria->index = 'value';
        $criteria->addColumnCondition(array(
            'item_type' =>  $this->shortTypeName,
        ));
        $allMenuItemValues = array_keys(MenuItem::model()->findAll($criteria));
        if (!count($allMenuItemValues)) {
            return $this;
        }

        $criteria = new DbCriteria();
        $criteria->select = $this->model->primaryKey();
        $criteria->index = $this->model->primaryKey();
        $criteria->addInCondition($this->model->primaryKey(), $allMenuItemValues);
        $existsRecordIds =array_keys($this->model->findAll($criteria));

        $notExistsValues = array_diff($allMenuItemValues, $existsRecordIds);
        if (!count($notExistsValues)) {
            return $this;
        }

        $criteria = new DbCriteria();
        $criteria->addInCondition('value', $notExistsValues);
        $criteria->addColumnCondition(array(
            'item_type' => $this->shortTypeName,
        ));
        MenuItem::model()->deleteAll($criteria);

        return $this;
    }


    public function init()
    {
        $this->clearMenuItems();
    }

    public function validate(MenuItem $item)
    {
        $numericalValidator = new CNumberValidator();
        $numericalValidator->integerOnly = true;
        $numericalValidator->allowEmpty = false;
        $numericalValidator->attributes = array('value');
        $numericalValidator->validate($item);

        if ($item->hasErrors('value')) {
            return false;
        }

        $existValidator = new CExistValidator();
        $existValidator->className = $this->modelClass;
        $existValidator->attributeName = $this->model->primaryKey();
        $existValidator->attributes = array('value');
        $existValidator->allowEmpty = false;
        $existValidator->validate($item);

        return $item->hasErrors('value');
    }
}
