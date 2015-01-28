<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 */
/* @var $this CrudCode */

    $moduleName = explode('/', $this->controller);
    if (count($moduleName) > 1) {
        $moduleName = lcfirst(reset($moduleName));
    } else {
        $moduleName = false;
    }

    $underscoredModelClass = str_replace('-', '_', $this->class2id($this->getModelClass()));
    $spacedModelClass = $this->class2name($this->getModelClass());
?>
<?php echo "<?php\n"; ?>

<?php if ($moduleName): ?>
/**
 * @property <?php echo ucfirst($moduleName).'Module' ?> $module
 * @method <?php echo ucfirst($moduleName).'Module' ?> getModule()
 * @property <?php echo $this->getModelClass() ?>|boolean $model the loaded model or false if not exists or not found.
 */
<?php endif ?>
class <?php echo $this->controllerClass; ?> extends Controller
{
    const GET_PARAM_NAME = '<?php echo $underscoredModelClass ?>_id';

    /**
     * @var <?php echo $this->getModelClass() ?>|null|boolean
     */
    private $_model = null;

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @return <?php echo $this->modelClass; ?>|boolean the loaded model or false if not founded
     */
    public function getModel()
    {
        if (is_null($this->_model)) {
            $id = $this->request->getQuery(self::GET_PARAM_NAME, false);
            if ($id !== false && is_scalar($id)) {
                $this->_model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
            }
            if (empty($this->_model)) {
                $this->_model = false;
            }
        }
        return $this->_model;
    }

    /**
     * @param <?php echo $this->modelClass ?>|boolean $model or false if empty model.
     * @throws CException if model has incorrect format.
     */
    public function setModel($model)
    {
        if ($model instanceof <?php echo $this->modelClass ?>) {
            $this->_model = $model;
        } elseif (empty($model)) {
            $this->_model = false;
        } else {
            throw new CException('Incorrect model type. <?php echo $this->modelClass ?> or false required.');
        }
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + '.implode(', ', array( // actions that we only allow via POST request
                'delete', 
            )),
            'modelExists + '.implode(', ', array( // check if model with current id exists
                'delete',
                'update',
                'view',
            )),
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            // Note: remove this rule or some actions if need
            array(
                'allow',  // allow for all users
                'actions'   =>  array(
                    'index',
                    'view',
                    'create',
                    'update',
                    'delete',
                ),
                'users'     =>  array('*'),
            ),
            // Note: remove this rule or some actions if need
            array(
                'allow', // allow for authenticated users
                'actions'   =>  array(
                    'index',
                    'view',
                    'create',
                    'update',
                    'delete',
                ),
                'users'     =>  array('@'),
            ),
            // Note: remove this rule or some actions if need
            array(
                'allow', // allow for admin users
                'actions'   =>  array(
                    'index',
                    'view',
                    'create',
                    'update',
                    'delete',
                ),
                'roles'     =>  array('admin'),
            ),
            array(
                'deny',  // deny all other users
                'users'     =>  array('*'),
            ),
        );
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
            throw new CHttpException(404, 'The requested <?php echo $spacedModelClass ?> does not exist.');
        } 
        $filterChain->run(); 
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
            $model->attributes = $this->request->getPost('<?php echo $this->modelClass; ?>', array());
            if ($model->saveWithTransaction()) {
                $this->redirect($this->createUrl('view', array(
                    self::GET_PARAM_NAME    =>  $model-><?php echo $this->tableSchema->primaryKey; ?>,
                )));
            }
        }

        $this->render($view, array(
            'model' =>  $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param <?php echo $this->modelClass; ?> $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if ($this->request->getPost('ajax', false) === '<?php echo $this->class2id($this->modelClass); ?>-form') {
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
        $this->_model = new <?php echo $this->modelClass; ?>('insert');
        $this->validateAndSave('create');
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate()
    {
        $this->getModel()->scenario = 'update';
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
        $dataProvider = new CActiveDataProvider('<?php echo $this->modelClass; ?>');
        $this->render('index', array(
            'dataProvider'  =>  $dataProvider,
        ));
    }
}
