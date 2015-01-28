<?php

class AdminController extends Controller
{
    public $breadcrumbs = array();

    public $menu = array();

    public $layout = '//layouts/admin/column2';

    public function filters()
    {
        return array_merge(array(
            array(
                'bootstrap.filters.BootstrapFilter'
            ),
            'accessControl',
        ), parent::filters(), array(
        ));
    }

    public function accessRules()
    {
        return array(
            array(
                'allow',
                'roles'         =>  array('admin'),
            ),
            array(
                'deny',
                'users'             =>  array('*'),
                'deniedCallback'    =>  array($this, 'loginRequired'),
            )
        );
    }

    public function loginRequired()
    {
        if (!$this->user->isGuest || $this->request->isAjaxRequest) {
            throw new CHttpException(403,Yii::t('yii','Login Required'));
        } else {
            $this->user->returnUrl = $this->request->url;
            $this->request->redirect(Yii::app()->createUrl('admin/auth/login'));
        }
    }
}
