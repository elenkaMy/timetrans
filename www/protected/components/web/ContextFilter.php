<?php

class ContextFilter extends CFilter
{
    public $context = Response::CONTEXT_HTML;

    protected function preFilter($filterChain)
    {
        if (!($filterChain->controller instanceof Controller)) {
            throw new CException('ContextFilter must have controller as instance of Controller');
        }

        switch ($this->context) {
            case Response::CONTEXT_JSON:
            case Response::CONTEXT_XML:
                Yii::app()->response->context = $this->context;
                break;
            case Response::CONTEXT_HTML:
                break;
            default:
                throw new CException("Unknown context {$this->context} in ContextFilter");
        }
        return true;
    }

    protected function postFilter($filterChain)
    {
        //
    }
}