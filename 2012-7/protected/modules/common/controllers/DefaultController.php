<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		//ssecho 124;exit;
		$this->render('index');
	}
}