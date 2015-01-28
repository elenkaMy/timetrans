<?php

class ProfileLogRoute extends CProfileLogRoute
{
    public $showInJsonResponses = false;

    /**
    * Renders the view.
    * @param string $view the view name (file name without extension). The file is assumed to be located under framework/data/views.
    * @param array $data data to be passed to the view
    */
    protected function render($view, $data)
    {
        switch (false) {
            case $this->showInJsonResponses:
            case Yii::app()->response->context === Response::CONTEXT_JSON:
                parent::render($view, $data);
                break;
            default:
                Yii::app()->response->data['_profile'] = $data;

                if ($this->report === 'summary') {
                    Yii::app()->response->data['_summary'] = array(
                        'time'      =>  sprintf('%0.5f',Yii::getLogger()->getExecutionTime()).'s',
                        'memory'    =>  number_format(Yii::getLogger()->getMemoryUsage()/1024).'KB',
                    );
                }

                break;
        }
    }

    /**
     * Displays the log messages.
     * @param array $logs list of log messages
     */
    public function processLogs($logs)
    {
        $app = Yii::app();
        if (!($app instanceof CWebApplication)) {
            return;
        }

        if ($this->getReport() === 'summary') {
            $this->displaySummary($logs);
        } else {
            $this->displayCallstack($logs);
        }
    }
}
