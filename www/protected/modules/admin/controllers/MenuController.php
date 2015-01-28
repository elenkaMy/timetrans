<?php


/**
 * @property AdminModule $module
 * @method AdminModule getModule()
 * @property Menu|boolean $model the loaded model or false if not exists or not found.
 */
class MenuController extends AdminController
{
    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new Menu();
        $model->unsetAttributes();
        $model->attributes = (array)$this->request->getQuery('Menu', array());

        $dataProvider = new CActiveDataProvider($model);

        $this->data = array_merge($this->data, array(
            'model'         =>  $model,
            'dataProvider'  =>  $dataProvider,
        ));

        if ($this->request->isAjaxRequest) {
            $this->renderPartial('_grid');
        } else {
            $this->render('index');
        }
    }
}
