<?php

class MmuserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/column2';

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
				'actions'=>array('view','admin','update','download'),
				'users'=>array('@'),
			),

			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    
     /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
     public function actionUpdate(){
         Mmuser::$db = SmartModel::getDbConnection();

        if(isset($_POST['Mmworks']))
        {
                $model = SmartModel::model('mm_works')->find('id=:workid',array('workid'=>$_GET['id']));
                $model->attributes=$_POST['Mmworks'];
                //var_dump($_POST['Mmworks']);exit;
                $model->save();
                //var_dump($model->getErrors());
                $this->redirect(array('view','userid'=>$model->user_id));
        }
     }
     
    /**
     * Displays a particular model.
     */     
    public function actionView()
    {
        Mmuser::$db = SmartModel::getDbConnection();
        
        if(isset($_GET['userid'])){
          $sql = "select * from mm_user mu where mu.user_id=:userid";
          $command = Yii::app()->pwind->createCommand($sql);
          $command->bindParam(':userid', $_GET['userid']);
          $res = $command->queryAll();
		  $sql = "select * from mm_works mw where mw.user_id=:userid order by mw.applytime desc";
          $command = Yii::app()->pwind->createCommand($sql);
          $command->bindParam(':userid', $_GET['userid']);
          $workinfo = $command->queryAll();
       }
       if($res===null)
           throw new CHttpException(404,'The requested page does not exist.');       
        $this->render('view',array(
            'model'=>$res,'workinfo'=>$workinfo,
        ));
    }
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        Mmuser::$db = SmartModel::getDbConnection();
		$model=new Mmuser('search');
		$model->unsetAttributes();  // clear any default values

		if(isset($_GET['Mmuser']))
			$model->attributes=$_GET['Mmuser'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
    /**
    * put your comment there...
    * download file
    */
    public function actionDownload(){
        Mmuser::$db = SmartModel::getDbConnection();        
        $model = $this->loadWorkmodel();
		$arrdir = '/act/files/activity/10/mm/'.$model->id.'_work.rar';
        header('Content-Disposition: attachment; filename='.$model->fileurl);

        readfile($arrdir);
        return;

    }
    
    /**
    * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
    */
    public function loadWorkmodel(){
        Mmuser::$db = SmartModel::getDbConnection();
            if(isset($_GET['wid']))
                $model=SmartModel::model('mm_works')->find('id='.$_GET['wid']);
            if($model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        return $model;
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
				$this->_model=Mmuser::model()->findbyPk($_GET['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='activity-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
