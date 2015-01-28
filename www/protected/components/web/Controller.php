<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 * 
 * @property array $data
 * @property-read WebUser $user alias for Yii::app()->user.
 * @property-read UserHelper $userHelper alias for Yii::app()->userHelper.
 * @property-read WebHttpRequest $request alias for Yii::app()->request.
 * @property-read Response $response alias for Yii::app()->response.
 * @property-read FileHelper $fileHelper alias for Yii::app()->fileHelper.
 */
class Controller extends CController
{
    private static $_forwardWithParamsInProcess = false;
    private static $_firstForwardCall;
    private static $_beforeEndForward;

    public $breadcrumbs = array();
    public $seoDescription;
    public $seoKeywords;

    /**
     * Alias for Yii::app()->user.
     * @return WebUser
     * @throws Exception if user component not exists
     */
    public function getUser()
    {
        if (!Yii::app()->hasComponent('user')) {
            throw new Exception('You must configure user component in main config.');
        }
        return Yii::app()->user;
    }

    /**
     * Alias for Yii::app()->userHelper.
     * @return UserHelper
     * @throws Exception if userHerlper component not exists
     */
    public function getUserHelper()
    {
        if (!Yii::app()->hasComponent('userHelper')) {
            throw new Exception('You must configure userHelper component in main config.');
        }
        return Yii::app()->userHelper;
    }

    /**
     * Alias for Yii::app()->request.
     * @return WebHttpRequest
     */
    public function getRequest()
    {
        return Yii::app()->request;
    }

    /**
     * Alias for Yii::app()->response.
     * @return Response
     */
    public function getResponse()
    {
        return Yii::app()->response;
    }

    /**
     * Alias for Yii::app()->fileHelper.
     * @return FileHelper
     */
    public function getFileHelper()
    {
        return Yii::app()->fileHelper;
    }

    public function getData()
    {
        return Yii::app()->response->data;
    }

    public function setData(array $data)
    {
        Yii::app()->response->data = $data;
    }

    public function renderPartial($view, $data = null, $return = false, $processOutput = false)
    {
        $data = empty(Yii::app()->response->data) ? $data : array_merge(Yii::app()->response->data, (array) $data);
        return parent::renderPartial($view, $data, $return, $processOutput);
    }

    public function getTitle()
    {
        return $this->pageTitle ? $this->pageTitle : Yii::app()->name;
    }

    /**
     * Processes the request using another controller action.
     * This is like {@link redirect}, but the user browser's URL remains unchanged.
     * In most cases, you should call {@link redirect} instead of this method.
     * @param string $route the route of the new controller action. This can be an action ID, or a complete route
     * with module ID (optional in the current module), controller ID and action ID. If the former, the action is assumed
     * to be located within the current controller.
     * @param boolean $exit whether to end the application after this call. Defaults to true.
     * @throws Exception
     */
    public function forward($route, $exit = true)
    {
        if (!self::$_forwardWithParamsInProcess) {
            return parent::forward($route, $exit);
        }

        if (self::$_firstForwardCall) {
            if ($exit) {
                throw new CException('Bad exit param value');
            }
            self::$_firstForwardCall = false;
            return parent::forward($route, false);
        } else {
            parent::forward($route, false);
            if ($exit) {
                self::$_forwardWithParamsInProcess = false;
                $beforeEndForward = self::$_beforeEndForward;
                $beforeEndForward();
                Yii::app()->end();
            }
        }
    }

    /**
     * Processes the request using another controller action.
     * This is like {@link redirect}, but the user browser's URL remains unchanged.
     * In most cases, you should call {@link redirect} instead of this method.
     * @param string $route the route of the new controller action. This can be an action ID, or a complete route
     * with module ID (optional in the current module), controller ID and action ID. If the former, the action is assumed
     * to be located within the current controller.
     * @param array|null $params
     * @param callable|null $callback
     * @throws Exception
     */

    public function forwardWithParams($route, array $params = array(), $callback = null)
    {
        if (self::$_forwardWithParamsInProcess) {
            new CException('Forward with params already in process.');
        }
        self::$_forwardWithParamsInProcess = true;

        $oldParams = array(
            'get'           =>  $_GET,
            'post'          =>  $_POST,
            'requestType'   =>  $this->request->requestType,
            'isAjax'        =>  $this->request->isAjaxRequest,
            'url'           =>  $this->request->url,
        );

        $setHttpParams = function ($params) {
            $_GET = $params['get'];
            $_POST = $params['post'];
            $_SERVER['REQUEST_METHOD'] = $params['requestType'];
            $_SERVER['HTTP_X_REQUESTED_WITH'] = $params['isAjax'] ? 'XMLHttpRequest' : null;
            Yii::app()->request->requestUri = $params['url'];
        };
        $setHttpParams(array(
            'get'           =>  $params,
            'post'          =>  array(),
            'requestType'   =>  'GET',
            'isAjax'        =>  false,
            'url'           =>  $this->createUrl($route, $params)
        ));

        $this->request->attachEventHandler('onBeforeRedirect', $beforeRedirectHandler = function($event) {
            throw new CException ('This page is redirected to '.$event->url);
        });

        $beforeEndForward = self::$_beforeEndForward = function() use (
            $oldParams,
            $callback,
            $setHttpParams,
            $beforeRedirectHandler
        ) {
            Yii::app()->request->detachEventHandler('onBeforeRedirect', $beforeRedirectHandler);
            $setHttpParams($oldParams);
            $callback ? $callback() : null;
        };

        try {
            self::$_firstForwardCall = true;
            $this->forward($route, false);
            $beforeEndForward();
            Yii::app()->end();
        } catch (Exception $e) {
            self::$_forwardWithParamsInProcess = false;
            $this->request->detachEventHandler('onBeforeRedirect', $beforeRedirectHandler);
            $setHttpParams($oldParams);
            throw $e;
        }
    }
}
