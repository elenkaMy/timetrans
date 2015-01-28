<?php

class WebLogRoute extends CWebLogRoute
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
                Yii::app()->response->data['_log'] = $data;
                break;
        }
    }
}