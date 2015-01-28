<?php

class BreadcrumbsHelper extends CApplicationComponent
{
    /**
     * @param CActiveRecord[] $records
     * @param string $route current route by default
     */
    public function byDbUrlRule(array $records, $route = null)
    {
        if (is_null($route)) {
            if (is_null(Yii::app()->controller)) {
                throw new CException('Current controller not founded. You must define $route param.');
            }
            $route = Yii::app()->controller->route;
        }

        return count($records)
            ?   array_combine(array_map(function (CActiveRecord $r) {
                    return (string) $r;
                }, $records), array_map(function (CActiveRecord $r) use ($route) {
                    return Yii::app()->createUrl($route, array('record' => $r));
                }, $records))
            :   array();
    }
}
