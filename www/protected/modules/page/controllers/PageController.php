<?php

class PageController extends Controller
{
    public function actionIndex(Page $record)
    {
        switch ($record->fixed_name) {
            case 'default':
                return $this->defaultAction();
        }
        
        $this->render('index', array(
            'page'  =>  $record,
        ));
    }
    
    protected function defaultAction()
    {
        $products = Product::model()->findAllByAttributes(array('visible' => 1));
        $this->render('default', array(
            'page'          =>  Page::byFixedName('default'),
            'products'      =>  $products,
        ));
    }
}