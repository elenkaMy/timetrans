<?php

/**
 * @property AdminModule $module
 * @method AdminModule getModule()
 */
class DefaultController extends AdminController
{
    public function actionIndex()
    {
        $this->render('index');
    }
}
