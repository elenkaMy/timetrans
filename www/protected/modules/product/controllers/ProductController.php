<?php 

class ProductController extends Controller
{
   /**
     * @var Product|null|boolean
     */
    private $_model = null;
    
    /**
    * @return array action filters
    */
    public function filters()
    {
        return array_merge(
            parent::filters(), array(
                array(
                    'application.components.web.ContextFilter + ' . implode(', ', array(
                        'add',
                        'delete',
                        'send',
                    )),
                    'context' => Response::CONTEXT_JSON,
                ),
                'ajaxOnly + ' . implode(', ', array(
                    'add',
                    'delete',
                    'send',
                )),
                'postOnly + ' . implode(', ', array(
                    'add',
                    'delete',
                )),
                'modelExists + ' . implode(', ', array(
                    'add',
                    'delete',
                )),
            )
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
            throw new CHttpException(404, 'The requested Product does not exist.');
        } 
        $filterChain->run(); 
    }

   
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @return Product|boolean the loaded model or false if not founded
     */
    public function getModel()
    {
        if (is_null($this->_model)) {
            $id = $this->request->getQuery('product_id', false);
            if ($id !== false && is_scalar($id)) {
                $this->_model = Product::model()->findByPk($id);
            }
            if (empty($this->_model)) {
                $this->_model = false;
            }
        }
        return $this->_model;
    }
    
    public function actionIndex(Product $record)
    {
        $this->render('index', array(
            'product' => $record,
        ));
    }
    
    public function actionAdd()
    {
        $product = $this->getModel();
        if ($product) {
            $this->_favourites()->addFavouritesProduct($product);
            $this->data = array(
                'success' => true,
                'content' => $this->renderPartial('_add', array(
                    'product' => $product,
                ), true)
            );
        } else {
            $this->data = array(
                'success' => false,
                'message' => 'Ошибка. Товар не найден.'
            );
        }
    }
    
    public function actionDelete()
    {
        $product = $this->getModel();
        if ($product) {
            $this->_favourites()->deleteFavouritesProduct($product);
            $this->data = array(
                'success' => true,
            );
        } else {
            $this->data = array(
                'success' => false,
                'message' => 'Ошибка. Товар не найден.'
            );
        }
    }
    
    private function _favourites()
    {
        return Yii::app()->favourites;
    }
    
    public function actionSend() 
    {
        $model = new SendForm('send');
        if ($this->request->isPostRequest) { 
            $model->attributes = $this->request->getPost('SendForm', array());
            if ($model->validate()) {
                if ($this->sendMail($model)) {
                    $this->data = array_merge($this->data, array(
                        'html' => 'Ваш заказ принят.',
                        'success' => true,
                    ));
                    return;
                } else {
                    $this->data = array_merge($this->data, array(
                        'html' => 'Заказ не отправлен. Повторите попытку.',
                        'success' => true,
                    ));
                    return;
                }
            } else {
                $this->data = array_merge($this->data, array(
                    'html' => $this->renderPartial('send-form', array(
                        'model' => $model,
                    ), true),
                    'success' => false,
                ));
                return;
            }
        }       
        $this->data = array_merge($this->data, array(
            'html' => $this->renderPartial('send-form', array(
                'model' => $model,
            ), true),
        ));
    }
    
    protected function sendMail(SendForm $form)
    {
        /* @var $mailer PHPMailer */
        $mailer = Yii::app()->mailer;
        $mailer->AddAddress(Setting::byFixedName('adminEmail')->value);

        $mailer->Subject = 'Хочу';
        
        $mailer->AltBody = $this->renderPartial('mail/_text-template', array(
            'form' => $form,
            'date'      =>  date('Y-m-d H:i:s'),
        ), true);
        $mailer->MsgHTML($this->renderPartial('mail/_html-template', array(
            'form' => $form,
            'date'      =>  date('Y-m-d H:i:s'),
        ), true));
        
        if (!$mailer->Send()) {
            
            Yii::log("Can not send mail to" . Setting::byFixedName('adminEmail')->value . ". Error occurred:  $mailer->ErrorInfo.", CLogger::LEVEL_WARNING);
            return false;
        }

        return true;
    }
}