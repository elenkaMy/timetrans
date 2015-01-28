<?php


/**
 * @property AdminModule $module
 * @method AdminModule getModule()
 * @property Setting|boolean $model the loaded model or false if not exists or not found.
 */
class SettingController extends AdminController
{
    const GET_PARAM_NAME = 'setting_id';

    /**
     * @var Setting|null|boolean
     */
    private $_model = null;

    /**
    * @return array action filters
    */
    public function filters()
    {
        return array_merge(array(
        ), parent::filters(), array(
            'modelExists + '.implode(', ', array( // check if model with current id exists
                'update',
                'view',
            )),
            'recordIsNotFixed + '.implode(', ', array(
                'delete',
            )),
        ));
    }

    /**
     * Checks if record with id from GET params exists
     * If the data model is not found, an HTTP exception will be raised.
     * @param CFilterChain $filterChain
     * @throws CHttpException
     */
    public function filterModelExists(CFilterChain $filterChain) 
    { 
        if (!$this->getModel()) { 
            throw new CHttpException(404, 'The requested Setting does not exist.');
        } 
        $filterChain->run(); 
    }

    /**
     * Not allow delete records with not null fixed_name
     * @param CFilterChain $filterChain
     * @throws CHttpException
     */
    public function filterRecordIsNotFixed(CFilterChain $filterChain)
    {
        if ($this->getModel()->fixed_name !== null) {
            throw new CHttpException(403, "You can't delete this setting.");
        }
        $filterChain->run();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @return Setting|boolean the loaded model or false if not founded
     */
    public function getModel()
    {
        if (is_null($this->_model)) {
            $id = $this->request->getQuery(self::GET_PARAM_NAME, false);
            if ($id !== false && is_scalar($id)) {
                $this->_model = Setting::model()->findByPk($id);
            }
            if (empty($this->_model)) {
                $this->_model = false;
            }
        }
        return $this->_model;
    }

    /**
     * @param Setting|boolean $model or false if empty model.
     * @throws CException if model has incorrect format.
     */
    public function setModel($model)
    {
        if ($model instanceof Setting) {
            $this->_model = $model;
        } elseif (empty($model)) {
            $this->_model = false;
        } else {
            throw new CException('Incorrect model type. Setting or false required.');
        }
    }

    /**
     * Displays a particular model.
     */
    public function actionView()
    {
        $this->render('view', array(
            'model' =>  $this->getModel(),
        ));
    }

    /**
     * Validates and saves model. If saving is successful, the browser
     * will be redirected to the 'view' page. Otherwise view template would be
     * rendered with model errors.
     * @param string $view template for rendering.
     */
    protected function validateAndSave($view)
    {
        $model = $this->getModel();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if ($this->request->isPostRequest) {
            $model->attributes = $this->request->getPost('Setting', array());
            if ($model->saveWithTransaction()) {
                $this->redirect($this->createUrl('view', array(
                    self::GET_PARAM_NAME    =>  $model->id,
                )));
            }
        }

        $this->render($view, array(
            'model' =>  $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param Setting $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if ($this->request->getPost('ajax', false) === 'setting-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate()
    {
        $this->getModel()->scenario = 'adminUpdate_'.$this->getModel()->setting_type;
        $this->validateAndSave('update');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new Setting('search');
        $model->unsetAttributes();
        $model->attributes = (array)$this->request->getQuery('Setting', array());

        $dataProvider = new CActiveDataProvider($model->search(), array(
            'pagination'    => array(
                'pageSize'      =>  Yii::app()->params['admin']['count-per-page'],
            ),
        ));

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
