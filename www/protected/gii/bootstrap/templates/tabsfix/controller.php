<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 */
/* @var $this BootstrapCode */
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


/**
<?php if ($moduleName): ?>
 * @property <?php echo ucfirst($moduleName).'Module' ?> $module
 * @method <?php echo ucfirst($moduleName).'Module' ?> getModule()
<?php endif ?>
 * @property <?php echo $this->getModelClass() ?>|boolean $model the loaded model or false if not exists or not found.
 */
class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass . "\n"; ?>
{
    const GET_PARAM_NAME = '<?php echo $underscoredModelClass ?>_id';

    /**
     * @var <?php echo $this->getModelClass() ?>|null|boolean
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
            throw new CHttpException(404, 'The requested <?php echo $spacedModelClass ?> does not exist.');
        } 
        $filterChain->run(); 
    } 

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
            $button = $this->request->getPost('another-button');
            if ($model->saveWithTransaction()) {
                if ($button == Yii::t('admin', 'Save and Create')) {
                    $this->redirect($this->createUrl('create'));
                } elseif ($button == Yii::t('admin', 'Apply')) {
                    $this->redirect($this->createUrl('update', array(
                        self::GET_PARAM_NAME    =>  $model-><?php echo $this->tableSchema->primaryKey; ?>,
                    )));
                } else {
                    $this->redirect($this->createUrl('view', array(
                        self::GET_PARAM_NAME    =>  $model-><?php echo $this->tableSchema->primaryKey; ?>,
                    )));
                }
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
        $this->_model = new <?php echo $this->modelClass; ?>('adminInsert');
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
        $model = new <?php echo $this->modelClass; ?>('search');
        $model->unsetAttributes();
        $model->attributes = (array)$this->request->getQuery('<?php echo $this->modelClass; ?>', array());

        $dataProvider = new CActiveDataProvider($model->search(), array(
            'pagination'    => array(
                'pageSize'      =>  Yii::app()->params['admin']['count-per-page'],
            ),
            /*
            'sort'          =>  array(
                'defaultOrder'  =>  array(
                    'created_at'    =>  CSort::SORT_DESC,
                ),
            ),
            */
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

    /*
    preview routes
    array('<?php echo $moduleName ?: 'admin'?>/<?php echo $m = lcfirst(substr($c = $this->controllerClass, 0, strpos($c, 'Controller'))) ?>/previewOnCreate',    'pattern' => 'ajax/<?php echo $moduleName ?: 'admin'?>/<?php echo $m ?>/preview'),
    array('<?php echo $moduleName ?: 'admin'?>/<?php echo $m ?>/previewOnUpdate',    'pattern' => 'ajax/<?php echo $moduleName ?: 'admin'?>/<?php echo $m ?>/<<?php echo $underscoredModelClass ?>_id:\d+>/preview'),
    array('<?php echo $moduleName ?: 'admin'?>/<?php echo $m ?>/viewPreview',        'pattern' => 'ajax/<?php echo $moduleName ?: 'admin'?>/<?php echo $m ?>/hash/<hash:\w+>/preview/'),
    */

    /*
    public function validateAndPreview($view)
    {
        $transaction = Yii::app()->db->beginTransaction();
        $session = Yii::app()->session->toArray();

        $model = $this->getModel();
        $model->attributes = $this->request->getPost('<?php echo $this->modelClass ?>');
        $self = $this;

        $rollbackFunc = function () use ($transaction, $session) {
            $transaction->rollback();
            foreach ($session as $key => $value) {
                Yii::app()->session[$key] = $value;
            }
        };

        if ($model->save()) {
            $model->refresh();
            $hash = Yii::app()->securityManager->generatePassword(12, 7, 5);

            $this->beginClip($hash);

            $this->forwardWithParams('model route', array(
                'id'        =>  $model->id,
                'record'    =>  $model,
            ), function() use ($self, $hash, $rollbackFunc){
                $self->endClip();
                $content = $self->clips[$hash];
                Yii::app()->previewCache->set('previews_'.$hash, $content, 24*60*60);

                $self->data = array(
                    'success' => true,
                    'url' => $self->createUrl('viewPreview', array('hash' => $hash)),
                );

                $rollbackFunc();
            });
        } else {
            $this->data = array(
                'success'   => false,
                'message'   => implode("\n", array_map(function ($errors) {
                    return implode("\n", $errors);
                }, $model->getErrors())),
            );
        }

        $rollbackFunc();
    }

    public function actionPreviewOnCreate()
    {
        $this->_model = new <?php echo $this->modelClass ?>('adminInsert');
        $this->validateAndPreview('create');
    }

    public function actionPreviewOnUpdate()
    {
        $this->getModel()->scenario = 'adminUpdate';
        $this->validateAndPreview('update');
    }

    public function actionViewPreview()
    {
        $hash = 'previews_'.Yii::app()->request->getQuery('hash');
        $content = Yii::app()->previewCache->get($hash);

        $viewFilePath = $this->getViewFile('viewPreview');
        $js = $this->renderFile($viewFilePath, null, true);

        $clientScript = new CClientScript();
        $clientScript->registerScript('view-preview', $js, CClientScript::POS_END);
        $clientScript->render($content);

        echo $content;
    }
    */

    /*
    change position route
    array('<?php echo $moduleName ?: 'admin' ?>/<?php echo $m ?>/changePosition',    'pattern' => 'ajax/<?php echo $moduleName ?: 'admin'?>/<?php echo $m ?>/<<?php echo $underscoredModelClass ?>_id:\d+>/changePosition'),
    */

    /*
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
    */
}
