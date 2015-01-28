<?php
/* @var $this PageController */
/* @var $page Page */

$this->pageTitle = $page->seo_title;
$this->seoDescription = $page->seo_description;
$this->seoKeywords = $page->seo_keywords;
$this->breadcrumbs = array_merge(
    Yii::app()->breadcrumbsHelper->byDbUrlRule(array_reverse($page->allParents)),
    array($page->page_name)
);
echo $page->content;
