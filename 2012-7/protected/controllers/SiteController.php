<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		//$this->redirect('/');
		return;
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	/*public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	
	 * Logs out the current user and redirect to homepage.
	 
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}*/
	
	public function actionActivity()
	{
		if(!isset($_GET['id']))
			throw new CHttpException(404,'The requested page does not exist.');
			
		$_model=Activity::model()->find('`key`=?', array($_GET['id']));
		
		
		
		//没有找到活动那个或者活动首页为空
		if($_model===null || $_model->homepage == null)
			throw new CHttpException(404,'The requested page does not exist.');	

		//活动已经结束
		if($_model->status != 'publish'
		 || ($_model->beginTime!=null && strtotime($_model->beginTime) > time())
		 || ($_model->endTime!=null && strtotime($_model->endTime) < time()))
		{
			if($_model->closepage == null)
			{
				throw new CHttpException(404,'The requested page does not exist.');	
			}else{
				echo $_model->closepage->render();
				return;
			}
		}
		$this->checkSession();
		//输出活动首页
		echo $_model->homepage->render();
		return;
	}
	
	public function actionActivityresource()
	{
		if(!isset($_GET['page']) || !isset($_GET['id']))
			throw new CHttpException(404,'The requested page does not exist.');
			
		//find by key
		$c = new CDbCriteria();
		$c->join = 'Inner join activity On activity.id=t.activityId';
		$c->condition = "`t`.`key`=:page And `activity`.`key`=:id And activity.status='publish'";
		$c->params = array(':page'=>$_GET['page'], ':id'=>$_GET['id']);
		$_model=ActivityResource::model()->find($c);
		
		if($_model===null){
			//find by id
			$c = new CDbCriteria();
			$c->join = 'Inner join activity On activity.id=t.activityId';
			$c->condition = "t.`id`=:page And activity.`key`=:id And activity.status='publish'";
			$c->params = array(':page'=>$_GET['page'], ':id'=>$_GET['id']);
			$_model=ActivityResource::model()->find($c);
			
			if($_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		$this->checkSession();
		echo $_model->render();
	}
	public function checkSession(){
		if(!MemberHelper::isGuest() && (!$_SESSION['mobile'] || !$_SESSION['mzone_user_bossdata'])){
			$userprofile = MemberHelper::getProfile();
			$_SESSION['mzone_winduid'] = $userprofile['uid'];
			$_SESSION['mzone_windpwd'] = MemberHelper::PwdCode($userprofile['password']);
			$_SESSION['mzone_username'] = $userprofile['username'];
			$_SESSION['mobile'] = $userprofile['authmobile'];
			$_SESSION['hide_mobile'] = CommonHelper::mzone_get_hide_mobile($userprofile['authmobile']);
			MzoneGetBossHelper::mzone_set_bossdata(array('uid' => $userprofile['uid'],'authmobile' => $userprofile['authmobile']));
		}
	}
}











