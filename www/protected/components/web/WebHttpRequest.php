<?php

/**
 * Class WebHttpRequest
 */
class WebHttpRequest extends CHttpRequest {

    private $_uri = null;

    /**
     * @return string
     */
    public function getRequestUri()
    {
        if (is_null($this->_uri)) {
            return parent::getRequestUri();
        }
        return $this->_uri;
    }

    /**
     * @param string $url
     * @return WebHttpRequest
     */
    public function setUrl($url)
    {
        return $this->setRequestUri($url);
    }

    /**
     * @param string $uri
     * @return WebHttpRequest
     */
    public function setRequestUri($uri)
    {
        $this->_uri = $uri;
        return $this;
    }
    /**
     * Redirects the browser to the specified URL.
     * @param string $url URL to be redirected to. Note that when URL is not
     * absolute (not starting with "/") it will be relative to current request URL.
     * @param boolean $terminate whether to terminate the current application
     * @param integer $statusCode the HTTP status code. Defaults to 302. See {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html}
     * for details about HTTP status code.
     */
    public function redirect($url, $terminate = true, $statusCode = 302)
    {
        if ($this->hasEventHandler('onBeforeRedirect')) {
            $this->onBeforeRedirect(new BeforeRedirectEvent($url, $terminate, $statusCode, $this));
        }
        return parent::redirect($url, $terminate, $statusCode);
    }

    public function onBeforeRedirect($event)
    {
        $this->raiseEvent('onBeforeRedirect', $event);
    }

    /**
     * Sends a file to user.
     * @param string $fileName file name
     * @param string $content content to be set.
     * @param string $mimeType mime type of the content. If null, it will be guessed automatically based on the given file name.
     * @param boolean $terminate whether to terminate the current application after calling this method
     * @param boolean $attachment send as attachment. True by default.
     */
    public function sendFile($fileName, $content, $mimeType = null, $terminate = true, $attachment = true)
    {
        if (!$attachment) {
            $removeDispositionHeaderFunc = function () {
                header_remove('Content-Disposition');
            };
            if ($terminate) {
                Yii::app()->response->attachEventHandler('onBeforeEndRequest', $removeDispositionHeaderFunc);
            }
        }

        if ($mimeType === null) {
            $mimeType = $this->detectMimeType($fileName, $content);
        }

        parent::sendFile($fileName, $content, $mimeType, $terminate);

        if (!$attachment) {
            $removeDispositionHeaderFunc();
        }
    }

    /**
     * @param string $fileName
     * @param string $content
     * @return string
     */
    protected function detectMimeType($fileName, $content)
    {
        if (($mimeType = CFileHelper::getMimeType($fileName)) === null) {
            $mimeType = 'text/plain';
        }
        if (strpos($mimeType, ';') === false && extension_loaded('mbstring')) {
            $encoding = mb_detect_encoding($content, array_merge(array(
                'UTF-8',
                'Windows-1251',
            ), mb_detect_order()));
            if ($encoding) {
                $mimeType = rtrim($mimeType)."; charset=$encoding";
            }
        }
        return $mimeType;
    }
}

/**
 * Class BeforeRedirectEvent
 */
class BeforeRedirectEvent extends CEvent
{
    public $url;
    public $terminate;
    public $statusCode;

    public function __construct($url, $terminate, $statusCode, $sender = null, $params = null)
    {
        $this->url = $url;
        $this->terminate = $terminate;
        $this->statusCode = $statusCode;
        parent::__construct($sender, $params);
    }
}
