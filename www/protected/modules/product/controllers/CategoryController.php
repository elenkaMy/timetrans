<?php

class CategoryController extends Controller
{
	public function actionIndex(ProductCategory $record)
	{
		$this->render('index', array(
            'category' => $record,
        ));
	}
}