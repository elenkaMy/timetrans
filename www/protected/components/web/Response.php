<?php

/**
 * @property string $context Output context (html, json or xml)
 */
class Response extends CApplicationComponent
{
    // const header names
    const CONTENT_TYPE = 'Content-type';
    const SET_COOKIE = 'Set-cookie';

    // const header values
    const CONTENT_TYPE_HTML = 'text/html';
    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_XML = 'application/xml';

    public static $HTTP_CODES = array(
        100 =>  'Continue',
        101 =>  'Switching Protocols',
        102 =>  'Processing',
        118 =>  'Connection timed out',
        200 =>  'OK',
        201 =>  'Created',
        202 =>  'Accepted',
        203 =>  'Non-Authoritative',
        204 =>  'No Content',
        205 =>  'Reset Content',
        206 =>  'Partial Content',
        207 =>  'Multi-Status',
        210 =>  'Content Different',
        300 =>  'Multiple Choices',
        301 =>  'Moved Permanently',
        302 =>  'Found',
        303 =>  'See Other',
        304 =>  'Not Modified',
        305 =>  'Use Proxy',
        307 =>  'Temporary Redirect',
        310 =>  'Too many Redirect',
        400 =>  'Bad Request',
        401 =>  'Unauthorized',
        402 =>  'Payment Required',
        403 =>  'Forbidden',
        404 =>  'Not Found',
        405 =>  'Method Not Allowed',
        406 =>  'Not Acceptable',
        407 =>  'Proxy Authentication Required',
        408 =>  'Request Time-out',
        409 =>  'Conflict',
        410 =>  'Gone',
        411 =>  'Length Required',
        412 =>  'Precondition Failed',
        413 =>  'Request Entity Too Large',
        414 =>  'Request-URI Too Long',
        415 =>  'Unsupported Media Type',
        416 =>  'Requested range unsatisfiable',
        417 =>  'Expectation failed',
        418 =>  'I’m a teapot',
        422 =>  'Unprocessable entity',
        423 =>  'Locked',
        424 =>  'Method failure',
        425 =>  'Unordered Collection',
        426 =>  'Upgrade Required',
        449 =>  'Retry With',
        450 =>  'Blocked by Windows Parental Controls',
        500 =>  'Internal Server Error',
        501 =>  'Not Implemented',
        502 =>  'Bad Gateway ou Proxy Error',
        503 =>  'Service Unavailable',
        504 =>  'Gateway Time-out',
        505 =>  'HTTP Version not supported',
        507 =>  'Insufficient storage',
        509 =>  'Bandwidth Limit Exceeded',
    );

    /**
     * @var array Custom data for output or rendering in view
     */
    public $data = array();

    /**
     * Directory for log file with fatal errors. May be used yii path.
     * @var string
     */
    public $fatalLogFileDir = 'application.runtime';
    /**
     * Name of log file for fatal errors.
     * @var string
     */
    public $fatalLogFileName = 'fatals.log';

    private $_onSendHeadersExecuted = false;

    const CONTEXT_JSON = 'json';
    const CONTEXT_HTML = 'html';
    const CONTEXT_XML = 'xml';

    /**
     * @var string html by default.
     */
    protected $currentContext = null;

    public function init()
    {
        Yii::app()->attachEventHandler('onError', array($this, 'onError'));
        Yii::app()->attachEventHandler('onException', array($this, 'onError'));
        Yii::app()->attachEventHandler('onBeginRequest', array($this, 'onBeginRequest'));
        Yii::app()->attachEventHandler('onEndRequest', array($this, 'onEndRequest'));
    }

    public function getContext()
    {
        return $this->currentContext;
    }

    public function setContext($value)
    {
        switch ($value) {
            case self::CONTEXT_HTML:
                $this->setHeader(self::CONTENT_TYPE, self::CONTENT_TYPE_HTML . '; charset=' . Yii::app()->charset);
                break;
            case self::CONTEXT_JSON:
                $this->setHeader(self::CONTENT_TYPE, self::CONTENT_TYPE_JSON . '; charset=' . Yii::app()->charset);
                break;
            case self::CONTENT_TYPE_XML:
                $this->setHeader(self::CONTENT_TYPE, self::CONTENT_TYPE_XML . '; charset=' . Yii::app()->charset);
                break;
            default:
                throw new CException("Unknown context $value in Response::setContext");
                break;
        }

        $this->currentContext = $value;
    }

    /**
     * Set header value. That headers will send only onEndRequest event
     * 
     * @param string $name
     * @param string $value
     * @throws CException If headers already sent
     */
    public function setHeader($name, $value, $replace = true)
    {
        $file = $line = null;
        if (headers_sent($file, $line)) {
            throw new CException("Headers already sent in $file at line $line");
        }

        header("$name: $value", $replace);
    }

    /**
     * Define start header like 'HTTP/1.1 200 OK'
     * @param int $httpCode 200 by default
     * @param string $httpStatus from Response::HTTP_CODES by default
     * @param string $httpVersion HTTP/1.1 by default
     * @return Response
     */
    public function defineStartHeader($httpCode = 200, $httpStatus = null, $httpVersion = 'HTTP/1.1')
    {
        if (is_null($httpStatus)) {
            if (!isset(self::$HTTP_CODES[$httpCode])) {
                throw new CException("Unknown response status for code $httpCode");
            }
            $httpStatus = self::$HTTP_CODES[$httpCode];
        }
        header("$httpVersion $httpCode $httpStatus");
        return $this;
    }

    /**
     * Get all headers list (sent & will send)
     * 
     * @return array of headers in $name => array of values format.
     */
    public function getHeadersList()
    {
        $list = headers_list();
        $result = array();

        foreach ($list as $header) {
            $matches = array();
            if (preg_match('/^([^:]+):\s*(.*)$/', $header, $matches)) {
                if (!isset($result[$matches[1]])) {
                    $result[$matches[1]] = array();
                }
                $result[$matches[1]][] = $matches[2];
            } else {
                trigger_error("Can not parse header $header");
            }
        }

        return $result;
    }

    /**
     * Get value of some header by name
     * 
     * @param string $name
     * @param bookean $last If true, last of header with current name will return, else array of values
     * @return array|string|false Return header value or boolean false if not founded
     * There is will return empty array if header not found and $last == true
     */
    public function getHeader($name, $last = true, $caseSensitive = false)
    {
        $list = $this->getHeadersList();
        $headers = array();
        if ($caseSensitive) {
            $headers = $list;
        } else {
            foreach ($list as $headerName => $value) {
                $headerName = strtolower($headerName);
                if (!isset($headers[$headerName])) {
                    $headers[$headerName] = array();
                }
                $headers[$headerName] = array_merge($headers[$headerName], $value);
            }
            $name = strtolower($name);
        }

        if (!empty($headers[$name])) {
            if ($last) {
                return array_pop($headers[$name]);
            }
            return $headers[$name];
        }

        if ($last) {
            return false;
        }
        return array();
    }

    public function onBeginRequest(CEvent $event)
    {
        if (!($event->sender instanceof CWebApplication)) {
            throw new CException('Response component can be used only for CWebApplication');
        }

        ob_start();

        if (is_null($this->context)) {
            $this->context = self::CONTEXT_HTML;
        }
    }

    public function clearDublicateCookies()
    {
        if (headers_sent() || !function_exists('header_remove')) {
            return false;
        }

        $cookies = array();
        $headerCookies = $this->getHeader(self::SET_COOKIE, false);
        foreach ($headerCookies as $value) {
            $matches = array();
            if (preg_match('/^([^=]+)=(.*)$/', $value, $matches)) {
                $cookies[$matches[1]] = $matches[2];
            } else {
                trigger_error("Can not parse cookie $value");
            }
            header_remove(self::SET_COOKIE);
        }
        foreach ($cookies as $name => $value) {
            $this->setHeader(self::SET_COOKIE, "$name=$value");
        }

        return true;
    }

    public function onSendHeaders()
    {
        if ($this->_onSendHeadersExecuted) {
            return $this;
        }
        $this->_onSendHeadersExecuted = true;
        $this->clearDublicateCookies();
    }

    public function onBeforeEndRequest(CEvent $event)
    {
        $this->raiseEvent('onBeforeEndRequest', $event);
    }

    protected function modifyContext()
    {
        if ($this->getContext() !== self::CONTEXT_JSON) {
            return $this;
        }

        switch ($this->getContext()) {
            case self::CONTEXT_JSON:
                echo CJSON::encode($this->data);
                break;
        }

        return $this;
    }

    protected function logErrorsIfExist()
    {
        $fatalErrorConsts = E_ERROR|E_PARSE|E_CORE_ERROR|E_CORE_WARNING|E_COMPILE_ERROR|E_COMPILE_WARNING;
        $error = error_get_last();
        if (is_null($error) || !($error['type'] & $fatalErrorConsts)) {
            return $this;
        }

        $dir = Yii::getPathOfAlias($this->fatalLogFileDir);
        $filename = $dir . DIRECTORY_SEPARATOR . $this->fatalLogFileName;
        $now = new DateTime();

        $content = '['.$now->format('Y-m-d H:i:s').']: '.print_r($error, true);
        file_put_contents($filename, $content, FILE_APPEND);
        return $this;
    }

    public function onEndRequest(CEvent $event)
    {
        $this->logErrorsIfExist();

        if ($this->hasEventHandler('onBeforeEndRequest')) {
            $this->onBeforeEndRequest(new CEvent());
        }

        $this->modifyContext();
        $this->onSendHeaders();
        @ob_end_flush();
    }

    public function onError(CEvent $event)
    {
        if ($this->getContext() === self::CONTEXT_JSON) {
            $this->data = array(
                'success'   =>  false,
            );
            $responseCode = 500;
            $responseMessage = self::$HTTP_CODES[$responseCode];

            if ($event instanceof CExceptionEvent) {
                $this->data['error'] = $event->exception->getMessage();
                if ($event->exception instanceof CHttpException) {
                    $responseCode = $event->exception->statusCode;
                    $this->data['code'] = $event->exception->statusCode . ': ' . get_class($event->exception);
                } else {
                    $this->data['code'] = get_class($event->exception);
                }
            } else {
                $this->data['error'] = $event->message;
                $this->data['code'] = $event->code;
            }

            ob_get_clean();
            ob_start();
            $this->defineStartHeader($responseCode, $responseMessage);

            $event->handled = true;
        }
    }
}