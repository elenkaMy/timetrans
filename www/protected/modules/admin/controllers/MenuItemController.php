<?php


/**
 * @property AdminModule $module
 * @method AdminModule getModule()
 * @property MenuItem|boolean $model the loaded model or false if not exists or not found.
 */
class MenuItemController extends AdminController
{
    const GET_PARAM_NAME = 'menu_item_id';

    /**
     * @var MenuItem|null|boolean
     */
    private $_model = null;

    /**
     * @var Menu|null|boolean
     */
    private $_menu = null;

    /**
    * @return array action filters
    */
    public function filters()
    {
        return array_merge(array(
        ), parent::filters(), array(
            array( // filter for ajax actions (responses will returned in json format)
                'application.components.web.ContextFilter + '.implode(', ', array(
                    'changePosition',
                )),
                'context'       =>  Response::CONTEXT_JSON,
            ),
            'ajaxOnly + '.implode(', ', array(
                'changePosition',
            )),
            'postOnly + '.implode(', ', array( // actions that we only allow via POST request
                'delete',
                'changePosition',
            )),
            'modelExists + '.implode(', ', array( // check if model with current id exists
                'delete',
                'update',
                'view',
            )),
            'menuExists + '.implode(', ', array( // check if model Menu with current id exists
                'index',
                'create',
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
            throw new CHttpException(404, 'The requested Menu Item does not exist.');
        } 
        $filterChain->run(); 
    } 

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @return MenuItem|boolean the loaded model or false if not founded
     */
    public function getModel()
    {
        if (is_null($this->_model)) {
            $id = $this->request->getQuery(self::GET_PARAM_NAME, false);
            if ($id !== false && is_scalar($id)) {
                $this->_model = MenuItem::model()->findByPk($id);
            }
            if (empty($this->_model)) {
                $this->_model = false;
            }
        }
        return $this->_model;
    }

    /**
     * @param MenuItem|boolean $model or false if empty model.
     * @throws CException if model has incorrect format.
     */
    public function setModel($model)
    {
        if ($model instanceof MenuItem) {
            $this->_model = $model;
        } elseif (empty($model)) {
            $this->_model = false;
        } else {
            throw new CException('Incorrect model type. MenuItem or false required.');
        }
    }

    /**
     * Checks if record with id from GET params exists
     * If the data model is not found, an HTTP exception will be raised.
     * @param CFilterChain $filterChain
     * @throws CHttpException
     */
    public function filterMenuExists(CFilterChain $filterChain)
    {
        if (!$this->getMenu()) {
            throw new CHttpException(404, 'The requested Menu does not exist.');
        }
        $filterChain->run();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @return Menu|boolean the loaded model or false if not founded
     */
    public function getMenu()
    {
        if (is_null($this->_menu)) {
            $id = $this->request->getQuery('menu_id', false);
            if ($id !== false && is_scalar($id)) {
                $this->_menu = Menu::model()->findByPk($id);
            }
            if (empty($this->_menu) && $this->model) {
                $this->_menu = $this->model->menu;
            }
            if (empty($this->_menu)) {
                $this->_menu = false;
            }
        }
        return $this->_menu;
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
        $menu = $this->getMenu();

        if ($this->request->isPostRequest) {
            $model->attributes = $this->request->getPost('MenuItem', array());
            $button = $this->request->getPost('another-button');
            if($model->isNewRecord) {
                $model->menu_id = $menu->id;
            };
            if ($model->saveWithTransaction()) {
                if($button == Yii::t('admin', 'Save and Create')){
                    $this->redirect($this->createUrl('create', array(
                        'menu_id'   => $menu->id,
                    )));
                } elseif($button == Yii::t('admin', 'Apply')){
                    $this->redirect($this->createUrl('update', array(
                        self::GET_PARAM_NAME    =>  $model->id,
                    )));
                } else {
                    $this->redirect($this->createUrl('view', array(
                        self::GET_PARAM_NAME    =>  $model->id,
                    )));
                }
            }
        }

        $this->render($view, array(
            'model' =>  $model,
            'menu'  =>  $menu,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param MenuItem $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if ($this->request->getPost('ajax', false) === 'menu-item-form') {
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
        $this->_model = new MenuItem('adminInsert');
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
            $this->redirect($this->request->getPost('returnUrl', $this->createUrl('index', array('menu_id' => $this->getModel()->menu_id))));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $menu = $this->getMenu();
        $model = new MenuItem('search');
        $model->unsetAttributes();
        $model->attributes = (array)$this->request->getQuery('MenuItem', array());

        $dataProvider = new CActiveDataProvider($model->search(), array(
            'pagination'    => array(
                'pageSize'      =>  Yii::app()->params['admin']['count-per-page'],
            ),
        ));

        $this->data = array_merge($this->data, array(
            'model'         =>  $model,
            'dataProvider'  =>  $dataProvider,
            'menu'  =>  $menu,
        ));

        if ($this->request->isAjaxRequest) {
            $this->renderPartial('_grid');
        } else {
            $this->render('index', array(
                'model' => $model->getItemsForMenu($menu->id),
                'menu' => $menu,
            ));
        }
    }

    public function actionChangePosition()
    {
        $model = $this->getModel();
        $model->scenario = 'changePosition';
        $oldPosition = $model->position;
        $model->position = $this->request->getPost('position');
        if($model->save()){
            $this->data = array(
                'success' => true,
            );
        } else {
            $this->data = array(
                'success' => false,
                'error'   => $model->getError('position'),
                'position' => $oldPosition,
            );
        };
    }
}
