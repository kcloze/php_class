<?php
Yii::import('ext.sinaWeibo.SinaWeibo',true);

class WeiboController extends Controller
{  
	
	public function actionIndex(){
		if(empty($_SESSION['mobile'])){
			$reurl='http://www.m-zone.cn/login.php?jumpurl='.urldecode($_GET['reurl']);
			header( "Location: $reurl");exit;
			//echo "<h1>您还没有登录动感官网，将会在3秒之后跳转到首页。如果没有，点击<a href='http://www.m-zone.cn'>这里</a>。</h1>";exit;
			
				
		}
		if(!defined('WB_LOGIN')){
			$weiboService=new SinaWeibo(WB_AKEY, WB_SKEY);
			$code_url = $weiboService->getAuthorizeURL( WB_CALLBACK_URL );
		}else {
			$code_url=WB_LOGIN;
		}
		!isset($_GET['reurl']) && $_GET['reurl']='http://www.m-zone.cn/act/atweibo/index';
		
		Yii::app()->cache->set('act_sina_weibo_url', $_GET['reurl'],1800);
		header("Location: $code_url");
	
		exit;
		
		
	}
	public function actionCallback(){
		$weiboService=new SinaWeibo(WB_AKEY, WB_SKEY);
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = WB_CALLBACK_URL;
			try {
				$token = $weiboService->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}
		
		if ($token) {
			$_SESSION['token'] = $token;
			
			$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
			$uid_get = $c->get_uid();
			$weibo_uid = $uid_get['uid'];
			
			
		
			
			$command=Yii::app()->db->createCommand();
			$users = $command->select('*')->from('act_sina_weibo_members')
			         ->where('mobile='.$_SESSION['mobile'])->queryRow();
			
			$user_message = $c->show_user_by_id( $weibo_uid);
			$_SESSION['sina_weibo_id']=$weibo_uid;
			$_SESSION['sina_weibo_name']=$user_message['screen_name'];
			
			$my_row=Yii::app()->db->createCommand('SELECT * FROM `act_sina_weibo_members` WHERE weiboUid='.$weibo_uid.' LIMIT 1')->queryRow();
			if(!empty($my_row) && $my_row['mobile']!=$_SESSION['mobile']){
				unset($_SESSION['sina_weibo_id']);
				unset($_SESSION['sina_weibo_name']);
				unset($_SESSION['token']);
				$_SESSION['atweibo_error']='<p>你登录的新浪微博账号<strong class="red">'.$my_row['weiboName'].'</strong>已经跟动感官网账号绑定过了，不能重复绑定！</p>';
				header( "Location: http://www.m-zone.cn/act/atweibo/error");exit;
			
			}
			$my_row=Yii::app()->db->createCommand('SELECT * FROM `act_sina_weibo_members` WHERE mobile='.$_SESSION['mobile'].' LIMIT 1')->queryRow();
			if(!empty($my_row) && $my_row['weiboUid']!=$_SESSION['sina_weibo_id']){
				unset($_SESSION['sina_weibo_id']);
				unset($_SESSION['sina_weibo_name']);
				unset($_SESSION['token']);
				$_SESSION['atweibo_error']='<p>你的动感官网绑定的新浪微博账号是<strong class="red">'.$my_row['weiboName'].'</strong>，请先到<a href="http://weibo.com" target="_blank" class="red">新浪微博</a>退出微博，用<strong class="red">'.$my_row['weiboName'].'</strong>重新登陆！</p>';
				header( "Location: http://www.m-zone.cn/act/atweibo/error");exit;
					
			}
			if(empty($users)){
				
				$command->reset();
				$command->insert('act_sina_weibo_members', array(
						'uid'=>$_SESSION['mzone_winduid'],
						'mobile'=>$_SESSION['mobile'],
						'weiboUid'=>$weibo_uid,
						'weiboName'=>$user_message['screen_name'],
						'updateTime'=>date('Y-m-d H:i:s'),
				));
			}
			$retUrl=Yii::app()->cache->get('act_sina_weibo_url');
			$retUrl=empty($retUrl)?'http://www.m-zone.cn/act/atweibo/index':$retUrl;
			setcookie( 'weibojs_'.$weiboService->client_id, http_build_query($token) );
			//header( "refresh:3;url=".$retUrl);
			//echo "<h1>认证已经通过，将会在3秒之后跳转到活动页面。如果没有，点击<a href=".$retUrl.">这里</a>。</h1>";exit;
			//header( "Location: ".$_SESSION['back_url']);exit;
			header( "Location: ".$retUrl);
			
		} else {
			
		    echo '认证失败';
		}
	}
	public function actionWeibolist(){
		if(empty($_SESSION['token'])){
	     	header( "Location: http://www.m-zone.cn/act/common/weibo/index");	
	    }
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$ms  = $c->friends_create('2798362404'); // done
		
		var_dump($ms);exit;
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
		
				
	}
	public function actionWeiboLogout(){
		if(empty($_SESSION['token'])){
			header( "Location: http://www.m-zone.cn/act/common/weibo/index");
		}
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$ms  = $c->account_end_session($_SESSION['token']['access_token']); // done
	
		var_dump($ms);exit;
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
	
	
	}
	
	
	
	
	
	
	
	
}