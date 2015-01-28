<?php

/**
 * @property AdminModule $module
 * @method AdminModule getModule()
 */
class AuthController extends AdminController
{
    public $layout = '//layouts/admin/standart';

    public function accessRules()
    {
        return array(
            array(
                'deny',
                'actions'   =>  array('login'),
                'users'     =>  array('@'),
                'verbs'     =>  array('GET'),
            ),
        );
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;

        // collect user input data
        if ($this->request->isPostRequest) {
            $model->attributes = $this->request->getPost('LoginForm', array());
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $returnUrl = '/' . (ltrim(Yii::app()->user->returnUrl, '/') ?: ltrim(Yii::app()->createUrl('admin/default/index'), '/'));
                $this->redirect($returnUrl);
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect($this->createUrl('login'));
    }
}