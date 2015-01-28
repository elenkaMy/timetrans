<?php

class DbProductUrlRule extends CBaseUrlRule
{
    /**
     * @var string
     */
    public $route;

    /**
     * @var string
     */
    public $categoryRoute;

    /**
     * @var string
     */
    public $prefix;

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

        if (isset($params['record']) && ($params['record'] instanceof Product)) {
            $record = $params['record'];
        } elseif (isset($params['id'])) {
            $id = $params['id'];
            unset($params['id']);

            $record = Product::model()->findByPk($id);
            if (empty($record)) {
                return false;
            }
        } else {
            return false;
        }

        unset($params['id']);
        unset($params['record']);

        $categoryUrl = ltrim($manager->removeUrlSuffix(Yii::app()->urlManager->createUrl($this->categoryRoute, array(
            'record'    =>  $record->category,
        )), $manager->urlSuffix), '/');
        return
            $categoryUrl.'/'.
            $record->alias.
            $manager->urlSuffix.
            (empty($params) ? '' : '?'.$manager->createPathInfo($params, '=', $ampersand)).
            (isset($anchor) ? $anchor : '');
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

        $parts = explode('/', $pathInfo);
        if (count($parts) < 2) {
            return false;
        }

        $productAlias = array_pop($parts);
        $category = $this->findCategoryRecursive(implode('/', $parts));
        if (empty($category)) {
            return false;
        }
        /* @var $category ProductCategory */

        $criteria = new DbCriteria();
        $criteria->alias = $alias = 't';
        $criteria->addColumnCondition(array(
            "$alias.category_id"    =>  $category->id,
        ));
        $product = $this->findRecordByAlias(Product::model(), $productAlias, $criteria);
        if (empty($product)) {
            return false;
        }

        /* @var $product Product */
        $product->category = $category;
        $_REQUEST['id'] = $_GET['id'] = $product->id;
        $_REQUEST['record'] = $_GET['record'] = $product;

        return $this->route;
    }

    /**
     * @param CActiveRecord $model
     * @param string $alias
     * @param string|DbCriteria $condition
     * @param array $params
     * @return ActiveRecord|null
     */
    protected function findRecordByAlias($model, $alias, $condition = '', $params = array())
    {
        $results = $model->findAllByAttributes(array(
            'alias' =>  $alias,
        ), $condition, $params);
        foreach ($results as $result) {
            if ($result->alias === $alias) {
                return $result;
            }
        }
        return null;
    }

    /**
     * @param string $pathInfo
     * @param ProductCategory $parent
     * @return ProductCategory|null
     */
    protected function findCategoryRecursive($pathInfo, ProductCategory $parent = null)
    {
        $paths = explode('/', $pathInfo);
        $firstPath = array_shift($paths);

        $criteria = new DbCriteria();
        $criteria->alias = $alias = 't';
        $criteria->with = array(
            'parentCategory'    =>  array(
                'together'          =>  true,
                'joinType'          =>  'LEFT OUTER JOIN',
            ),
        );

        if (is_null($parent)) {
            $criteria->addInCondition("$alias.parent_category_id", array(null));
        } else {
            $criteria->addInCondition("$alias.parent_category_id", array($parent->id));
        }

        $category = $this->findRecordByAlias(ProductCategory::model(), $firstPath, $criteria);
        if (is_null($category)) {
            return null;
        } else {
            if (!is_null($parent)) {
                $category->parentCategory = $parent;
            }
            if (count($paths)) {
                return $this->findCategoryRecursive(implode('/', $paths), $category);
            } else {
                return $category;
            }
        }
    }
}
