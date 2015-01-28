<?php

class DbUrlRule extends CBaseUrlRule
{
    /**
     * @var string
     */
    public $route;

    /**
     * @var string url prefix.
     */
    public $prefix;

    /**
     * @var string db model classname.
     */
    public $model;

    /**
     * @var string db table url field.
     */
    public $urlField = 'alias';

    /**
     * @var string optional (for db query count optimizing)
     */
    public $parentRelation;

    /**
     * @return CActiveRecord
     */
    protected function getModelObj()
    {
        if (is_null($this->model)) {
            throw new CException('Model property is required for DbUrlRule.');
        }
        if (is_string($this->model)) {
            $model = call_user_func(array($this->model, 'model'));
        }
        if (!($model instanceof CActiveRecord)) {
            throw new CException('Model property must be an CActiveRecord instance or class name.');
        }

        return $model;
    }

    /**
     * Creates a URL based on this rule.
     * @param CUrlManager $manager the manager
     * @param string $route the route
     * @param array $params list of parameters (name=>value) associated with the route
     * @param string $ampersand the token separating name-value pairs in the URL.
     * @return mixed the constructed URL. False if this rule does not apply.
     */
    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ($route !== $this->route) {
            return false;
        }

        if (array_key_exists('#', $params)) {
            $anchor = '#'.$params['#'];
            unset($params['#']);
        }

        if (!isset($params['id']) && !isset($params['record'])) {
            return false;
        }

        if (isset($params['record']) && ($params['record'] instanceof $this->model)) {
            $record = $params['record'];
        } elseif (isset($params['id'])) {
            $id = $params['id'];
            unset($params['id']);

            $model = $this->getModelObj();
            $record = $model->findByPk($id);
            if (empty($record)) {
                return false;
            }
        } else {
            return false;
        }

        unset($params['id']);
        unset($params['record']);

        $urlValue = $this->getUrlValue($record);
        return $this->prefix.$urlValue.
            (empty($urlValue) && $urlValue !== '0' && strpos($manager->urlSuffix, '/') === 0 ? '' : $manager->urlSuffix).
            (empty($params) ? '' : '?'.$manager->createPathInfo($params, '=', $ampersand)).
            (isset($anchor) ? $anchor : '');
    }

    /**
     * @param CActiveRecord $record
     * @return string url with parents recursive
     */
    protected function getUrlValue(CActiveRecord $record)
    {
        switch (false) {
            case !is_null($this->parentRelation):
            case $parent = $record->{$this->parentRelation}:
            case is_a($parent, get_class($this->getModelObj())):
                return $record->{$this->urlField};
            default:
                $parentUrlValue = $this->getUrlValue($parent);
                return ($parentUrlValue === '' ? '' : $parentUrlValue.'/').$record->{$this->urlField};
        }
    }

    /**
     * Parses a URL based on this rule.
     * @param CUrlManager $manager the URL manager
     * @param CHttpRequest $request the request object
     * @param string $pathInfo path info part of the URL (URL suffix is already removed based on {@link CUrlManager::urlSuffix})
     * @param string $rawPathInfo path info that contains the potential URL suffix
     * @return mixed the route that consists of the controller ID and action ID. False if this rule does not apply.
     */
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        if (!is_null($this->prefix) && $this->prefix !== '') {
            if (strpos($pathInfo, $this->prefix) !== 0) {
                return false;
            }
            $pathInfo = substr($pathInfo, strlen($this->prefix));
        }

        $record = $this->findModelRecursive($pathInfo);
        if (empty($record)) {
            return false;
        }

        $_REQUEST['id'] = $_GET['id'] = $record->id;
        $_REQUEST['record'] = $_GET['record'] = $record;

        return $this->route;
    }

    /**
     * @param CActiveRecord|null $pathInfo
     * @param string $condition
     * @param array $params
     * @return CActiveRecord|null
     */
    public function findModelByPathInfo($pathInfo, $condition = '', $params = array())
    {
        $results = $this->getModelObj()->findAllByAttributes(array(
            $this->urlField =>  $pathInfo,
        ), $condition, $params);
        foreach ($results as $result) {
            if ($result->{$this->urlField} === $pathInfo) {
                return $result;
            }
        }
        return null;
    }

    /**
     * @param string $pathInfo
     * @param CActiveRecord $parent
     * @return CActiveRecord|null
     */
    public function findModelRecursive($pathInfo, CActiveRecord $parent = null)
    {
        if (is_null($this->parentRelation)) {
            return $this->findModelByPathInfo($pathInfo);
        }

        $paths = explode('/', $pathInfo);
        $firstPath = array_shift($paths);

        $criteria = new DbCriteria();
        $criteria->with = array(
            $this->parentRelation   =>  array(
                'together'          =>  true,
                'joinType'          =>  'LEFT OUTER JOIN',
            ),
        );

        $keyFields = (array) $this->getModelObj()->metaData->tableSchema->primaryKey;
        if (!count($keyFields)) {
            throw new CException('Cannot retrieve key fields of '.$parent->tableName().' table.');
        }

        if (is_null($parent)) {
            foreach ($keyFields as $key) {
                $criteria->addInCondition("$this->parentRelation.$key", array(null));
            }
            $emptyAliasCriteria = new DbCriteria();
            $emptyAliasCriteria->addColumnCondition(array(
                "$this->parentRelation.$this->urlField" =>  '',
            ));
            $emptyAliasCriteria->addInCondition("$this->parentRelation.$this->urlField", array(null), 'OR');
            $criteria->mergeWith($emptyAliasCriteria, 'OR');
        } else {
            $self = $this;
            $criteria->addColumnCondition(array_combine(array_map(function ($field) use ($self) {
                return "$self->parentRelation.$field";
            }, $keyFields), array_map(function ($keyField) use($parent) {
                return $parent->{$keyField};
            }, $keyFields)));
        }

        $record = $this->findModelByPathInfo($firstPath, $criteria);
        if (is_null($record)) {
            return null;
        } else {
            if (!is_null($parent)) {
                $record->{$this->parentRelation} = $parent;
            }
            if (count($paths)) {
                return $this->findModelRecursive(implode('/', $paths), $record);
            } else {
                return $record;
            }
        }
    }
}
