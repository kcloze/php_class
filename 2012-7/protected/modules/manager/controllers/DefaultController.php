<?php

class DefaultController extends ManagerController
{
	public function actionIndex()
	{
		//$model = SmartModel::model('pw_zhgx_user');
		/*$phpwind = Yii::app()->pwind;
		$command = $phpwind->createCommand('select * from pw_zhgx_user');
		$rows = $command->queryAll();
		*/
		
		/*$model = SmartModel::model('pw_zhgx_user')->findAll('id=1');
		echo CHtml::dropDownList($name, $select, $data)('','post',array('id'=>'sadf'));
		CHtml::radioButton($name)
		CHtml::submitButton('注册',array('onclick'=>'mc_login()'))
		*/
//		$db2 = Yii::app()->db2;
		
		
		/**
		 * 
		 * Enter description here ...
		 * @var CActiveRecord
		 */
		//$sm = SmartModel::model('admin');
		//var_dump($sm->rules());
		$this->render('index');
	}
}