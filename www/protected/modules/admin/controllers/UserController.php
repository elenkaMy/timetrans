<?php


/**
 * @property AdminModule $module
 * @method AdminModule getModule()
 * @property User|boolean $model the loaded model or false if not exists or not found.
 */
class UserController extends AdminController
{
    const GET_PARAM_NAME = 'user_id';

    /**
     * @var User|null|boolean
     */
    private $_model = null;

    /**
    * @return array action filters
    */
    public function filters()
    {
        return array_merge(array(
        ), parent::filters(), array(
            'postOnly + '.implode(', ', array( // actions that we only allow via POST request
                'delete', 
            )),
            'modelExists + '.implode(', ', array( // check if model with current id exists
                'delete',
                'update',
                'view',
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
            throw new CHttpException(404, 'The requested User does not exist.');
        } 
        $filterChain->run(); 
    } 

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @return User|boolean the loaded model or false if not founded
     */
    public function getModel()
    {
        if (is_null($this->_model)) {
            $id = $this->request->getQuery(self::GET_PARAM_NAME, false);
            if ($id !== false && is_scalar($id)) {
                $this->_model = User::model()->findByPk($id);
            }
            if (empty($this->_model)) {
                $this->_model = false;
            }
        }
        return $this->_model;
    }

    /**
     * @param User|boolean $model or false if empty model.
     * @throws CException if model has incorrect format.
     */
    public function setModel($model)
    {
        if ($model instanceof User) {
            $this->_model = $model;
        } elseif (empty($model)) {
            $this->_model = false;
        } else {
            throw new CException('Incorrect model type. User or false required.');
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
            $model->attributes = $this->request->getPost('User', array());
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
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if ($this->request->getPost('ajax', false) === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->_model = new User('adminInsert');
        $this->validateAndSave('create');
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate()
    {
        $this->getModel()->scenario = 'adminUpdate';
        $this->validateAndSave('update');
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete()
    {
        $this->getModel()->deleteWithTransaction();

        if (!$this->request->getQuery('ajax', false)) {
            $this->redirect($this->request->getPost('returnUrl', $this->createUrl('index')));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new User('search');
        $model->unsetAttributes();
        $model->attributes = (array)$this->request->getQuery('User', array());

        $dataProvider = new CActiveDataProvider($model->search(), array(
            'pagination'    => array(
                'pageSize'      =>  Yii::app()->params['admin']['count-per-page'],
            ),
            'sort'          =>  array(
                'defaultOrder'  =>  array(
                    'created_at'    =>  CSort::SORT_DESC,
                ),
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
