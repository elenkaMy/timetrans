<?php

/**
 * Class PreviewWidget
 */
class PreviewWidget extends CWidget
{
    public $url = null;
    public $formId = null;

    public $htmlOptions = array();

    public function init()
    {
        if (is_null($this->url)) {
            throw new CException('Url required for PreviewWidget.');
        }
        if (is_null($this->formId)) {
            throw new CException('Form ID required for PreviewWidget.');
        }

        if (isset($this->htmlOptions['id'])) {
            $this->id = $this->htmlOptions['id'];
        } else {
            $this->htmlOptions['id'] = $this->id = sha1($this->formId.'_preview');
        }

        $this->htmlOptions['class'] =
            (isset($this->htmlOptions['class']) ? $this->htmlOptions['class'].' ' : '').
            'btn btn-info preview';

        parent::init();
    }


    public function run()
    {
        $this->render('preview/preview');
    }
}

