<?php

class ActivityresourceController extends ManagerController
{

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('admin','act_admin'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','admin','update','adminzt','updatezt'),
				'users'=>array('admin','act_admin'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('adminzt','updatezt','createzt'),
				'users'=>array('zhuanti'),
			),			
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$_model = $this->loadModel();
		echo $_model->render(true);die;
		$this->render('view',array(
			'model'=>$this->loadModel(),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ActivityResource;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ActivityResource']))
		{
			$model->attributes=$_POST['ActivityResource'];
			if($model->save())
				$this->redirect(array('admin','ActivityResource[activityId]'=>$model->activityId));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionCreatezt()
	{
		$model=new ActivityResource;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ActivityResource']))
		{
			$adata = Activity::model()->findbyPk($_POST['ActivityResource']['activityId']);
			if($adata['activityType'] !='1'){
				echo 'The requested page does not exist,not allow to edit!';
				exit();
			}				
			
			$model->attributes=$_POST['ActivityResource'];
			if($model->save())
				$this->redirect(array('adminzt','ActivityResource[activityId]'=>$model->activityId));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}	

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ActivityResource']))
		{
				$model->attributes=$_POST['ActivityResource'];
				$model->save();
			/*if($model->writekey != $_POST['ActivityResource']['writekey']){
				echo '文件已经被更改，请重新读取';
				return;
			}else{
				$model->attributes=$_POST['ActivityResource'];
				$model->save();
				
			}*/
		}
		
		//$model->writekey = microtime();
		//$model->save(true, array('writekey'));
		
		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionUpdatezt()
	{
		$model=$this->loadModel();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		//只能管理专题类别  zwy
		$adata = Activity::model()->findbyPk($model['activityId']);
		if($adata['activityType'] !='1'){
			echo 'The requested page does not exist,not allow to edit!';
			exit();
		}		
        
		if(isset($_POST['ActivityResource']))
		{
				$model->attributes=$_POST['ActivityResource'];
				$model->save();
			/*if($model->writekey != $_POST['ActivityResource']['writekey']){
				echo '文件已经被更改，请重新读取';
				return;
			}else{
				$model->attributes=$_POST['ActivityResource'];
				$model->save();
				
			}*/
		}
		
		//$model->writekey = microtime();
		//$model->save(true, array('writekey'));
		
		$this->render('updatezt',array(
			'model'=>$model,
		));
	}	

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel()->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ActivityResource');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ActivityResource('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['ActivityResource']))
			$model->attributes=$_GET['ActivityResource'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdminzt()
	{
		//只能管理专题类别  zwy
		$adata = Activity::model()->findbyPk($_GET['ActivityResource']['activityId']);
		if($adata['activityType'] !='1'){
			echo 'The requested page does not exist.';
			exit();
		}
		/*
		$criteria=new CDbCriteria;
		$criteria->condition = 'activityId=:activityId';
		$criteria->params = array(':activityId'=>$_GET['activityId']);		
        ActivityResource::model()->	
        */
		
		$model=new ActivityResource('searchAct');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['ActivityResource']))
			$model->attributes=$_GET['ActivityResource'];

		$this->render('adminzt',array(
			'model'=>$model,
		));
	}	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=ActivityResource::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='activity-resource-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
