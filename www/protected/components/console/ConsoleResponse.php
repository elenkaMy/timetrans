<?php

class ConsoleResponse extends CApplicationComponent
{
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

    public function init()
    {
        Yii::app()->attachEventHandler('onBeginRequest', array($this, 'onBeginRequest'));
        Yii::app()->attachEventHandler('onEndRequest', array($this, 'onEndRequest'));
    }

    public function onBeginRequest(CEvent $event)
    {
        if (!($event->sender instanceof CConsoleApplication)) {
            throw new CException('ConsoleResponse component can be used only for CConsoleApplication');
        }
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
    }
}