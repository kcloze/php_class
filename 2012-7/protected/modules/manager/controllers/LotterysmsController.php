<?php

class LotterySmsController extends ManagerController
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
				'actions'=>array('index','view','export'),
				'users'=>array('admin','act_admin'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','admin','update'),
				'users'=>array('admin','act_admin'),
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
		$model=new LotterySms;
        
        $model->lotteryId = $_GET['LotterySms']['lotteryId'];

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['LotterySms']))
		{
			$model->attributes=$_POST['LotterySms'];
            if($_POST['count']>0){
                for($i=0; $i< $_POST['count']; $i++)
                {
                    $model->id = null;
                    $model->setIsNewRecord(true);
                    $model->save();
                }
            }
            $this->redirect(array('admin','LotterySms[lotteryId]'=>$model->lotteryId));
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

		if(isset($_POST['LotterySms']))
		{
			$model->attributes=$_POST['LotterySms'];
			if($model->save())
				$this->redirect(array('admin','LotterySms[lotteryId]'=>$model->lotteryId));
		}

		$this->render('update',array(
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
		$dataProvider=new CActiveDataProvider('LotterySms');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new LotterySms('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['LotterySms']))
            $model->attributes=$_GET['LotterySms'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }
  public function actionExport()
    {
    	
        set_time_limit(0);
        if(isset($_GET['LotterySms'])){
            //$model->attributes=$_GET['LotterySms'];
           $lotteryId=intval($_GET['LotterySms']['lotteryId']);
            $commond=Yii::app()->db->createCommand('SELECT A.toPhone,A.editTime,A.username,B.id_number,B.realname,C.area FROM mzone_act.lotterysms AS A
				                  LEFT JOIN mzone.pw_members  AS B ON A.toPhone=B.authmobile 
				                  LEFT JOIN mzone.pw_mzone_bossdata  AS C ON A.toPhone=C.mobile   
				                  WHERE lotteryId='.$lotteryId.' AND toPhone IS NOT NULL ORDER BY A.id ASC');
            $data=$commond->queryAll();
                $header=array();
                
            	$header['toPhone']='手机';
            	$header['editTime']='中奖时间';
            	$header['username']='用户昵称';
            	$header['id_number']='身份证';
            	$header['realname']='真实姓名';
            	$header['city']='地市';
            	$header['name']='奖品名称';
            	
            foreach($data as $k=>$val){
            	
            	$data[$k]['name']=$_GET['LotterySms']['name'];
            	$data[$k]['editTime']=date('Y-m-d H:i:s',$data[$k]['editTime']);
            }
            array_unshift($data,$header);
            //var_dump($data);exit;
        }
      
				Yii::import('application.extensions.phpexcel.JPhpExcel');
				$xls = new JPhpExcel('UTF-8', false, date('m-d').'名单');
				$xls->addArray($data);
				$xls->generateXML(date('m-d').'名单');
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
				$this->_model=LotterySms::model()->findbyPk($_GET['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='LotterySms-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
