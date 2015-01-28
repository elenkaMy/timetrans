<?php

Yii::import('zii.widgets.CMenu', true);

class MainMenu extends CMenu
{
    public $breadcrumbs = array();
    public $activateParents = true;

    protected function isItemActive($item,$route)
    {
        $normalizedUrl = isset($item['url'])
            ? CHtml::normalizeUrl($item['url'])
            : null;
        if(isset($normalizedUrl)){
            if ($this->compareItemAndUrl($normalizedUrl, Yii::app()->request->url)) {
                return true;
            }

            foreach ($this->breadcrumbs as $label => $url) {
                if (!is_int($label) && $this->compareItemAndUrl($url, $normalizedUrl)) {
                    return true;
                }
            }
        }
        return parent::isItemActive($item, $route);
    }

    public function compareItemAndUrl($url1, $url2)
    {
        $suffix = Yii::app()->urlManager->urlSuffix;
        $url1 = ltrim(Yii::app()->urlManager->removeUrlSuffix($url1, $suffix), '/');
        $url2 = ltrim(Yii::app()->urlManager->removeUrlSuffix($url2, $suffix), '/');

        return $url1 === $url2;
    }
}