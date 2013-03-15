<?php
class MemberHelper{
	/**
	 * 用户登陆状态
	 */
	public static function isGuest()
	{
		if(self::getUid()){
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * 获取用户id
	 */
	public static function getUid()
	{
        if($_GET['da']==1){/*
            var_dump($GLOBALS['pwServer']['HTTP_USER_AGENT']);
            var_dump(GetCookie('winduser'));
            var_dump(StrCode(GetCookie('winduser'),'DECODE'));
            var_dump($_SESSION);echo '----';
            var_dump($_COOKIE);*/
            var_dump($_SESSION['mzone_http_user_agent']);
        }
        (!isset($_SESSION['mzone_http_user_agent']) ||!$_SESSION['mzone_http_user_agent']) && $_SESSION['mzone_http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		list($actuid) = explode("\t",addslashes(self::strCode(self::getCookie('winduser'),'DECODE')));
		$actuid = is_numeric($actuid)?$actuid:'';
		if(!$actuid)
			$actuid = (isset($_SESSION['mzone_winduid']) && $_SESSION['mzone_winduid'])?  $_SESSION['mzone_winduid'] : '';
		return $actuid;
	}
	/**
	 * 获取用户资料
	 * @return array
	 */
	public static function getProfile($uid='')
	{
		$res = array();
		$actuid = empty($uid) ? self::getUid() : $uid;
		$sql = 'SELECT m.uid,m.username,m.authmobile,m.password,md.money 
				FROM pw_members m 
				LEFT JOIN pw_memberdata md ON m.uid=md.uid 
				WHERE m.uid=:uid';
		$command = Yii::app()->mzone->createCommand($sql);
		$command->bindParam(':uid', $actuid, PDO::PARAM_STR);
		$res = $command->queryRow();
		if(empty($res)) return $res;
		$res['hideMobile'] = (strlen($res['authmobile']) == 11) ? substr_replace($res['authmobile'],'****',6,4) : $res['authmobile'];
		//头像
		return $res;
	}
    
    /**
    * 获取用户昵称
    * 
    * @param mixed $uid
    * @add 11.03.30
    * @return mixed
    */
    public static function getUsername($uid=''){
        $userinfo = self::getProfile($uid);
        return $userinfo['username'];
    }
			
	/**
	 * 获取用户手机品牌信息
	 */
	public static function getBoss()
	{
		//print_r($_SESSION['mzone_user_bossdata']);
		return $_SESSION['mzone_user_bossdata'];
	}
	
	
	
	public static function getLoginHash(){
		$onlineip = CommonHelper::getIp();
		return self::getVerify($onlineip,Yii::app()->params['db_pptkey']);
	}
	
	public static function getUserVerifyHash($str,$app=null){
		empty($app) && $app = Yii::app()->params['mzoneSiteid'];
		return substr(md5($str . $app . self::getServer('HTTP_USER_AGENT')), 8, 8);
	}
	
	/**
	 * 根据键值获取cookie值
	 */
	public static function getCookie($key){
		return isset($_COOKIE[self::cookiePre () . '_' . $key]) ? $_COOKIE[self::cookiePre () . '_' . $key] : '';
	}
	/**
	 * cookie键值前缀
	 */
	public static function cookiePre() {
		//return (Yii::app()->params['db_cookiepre']) ? Yii::app()->params['db_cookiepre'] : substr(md5($GLOBALS['db_sitehash']), 0, 5);
		
		$pre = (Yii::app()->params['mzoneCookiePre']) ? Yii::app()->params['mzoneCookiePre'] : substr ( md5 ( Yii::app()->params['siteHashMzoneV2'] ), 0, 5 );
		return $pre;
	}
	
	public static function strCode($string,$action='ENCODE'){
		$action != 'ENCODE' && $string = base64_decode($string);
		$code = '';
		$server_agent = self::getServer('HTTP_USER_AGENT');
		$key  = substr(md5($server_agent.Yii::app()->params['siteHash']),8,18);
		$keylen = strlen($key); $strlen = strlen($string);
		for ($i=0;$i<$strlen;$i++) {
			$k		= $i % $keylen;
			$code  .= $string[$i] ^ $key[$k];
		}
		return ($action!='DECODE' ? base64_encode($code) : $code);
	}

	/**
	 * 读取指定的全局环境变量值
	 */
	public static function getServer($keys){
		//Copyright (c) 2003-09 PHPWind
		foreach ((array)$keys as $key) {
			$server[$key] = NULL;
			if (isset($_SERVER[$key])) {
				$server[$key] = str_replace(array('<','>','"',"'",'%3C','%3E','%22','%27','%3c','%3e'),'',$_SERVER[$key]);
			}
		}
		return is_array($keys) ? $server : $server[$keys];
	}
	
	/**
	 * 验证码
	 */
	public static function getVerify($str,$app = null) {
		empty($app) && $app = Yii::app()->params['db_pptkey'];
		return substr(md5($str.$app.self::getServer('HTTP_USER_AGENT')),8,8);
	}

	/**
	 * 获取ip
	 */
	public static function getOnLineIP(){
		//$onlineip = 'Unknown';

		$pwServer = self::GetServer(array('HTTP_X_REAL_IP','HTTP_REFERER','HTTP_HOST','HTTP_X_FORWARDED_FOR','HTTP_USER_AGENT','HTTP_CLIENT_IP','HTTP_SCHEME','HTTPS',
							'PHP_SELF','REQUEST_URI','REQUEST_METHOD','REMOTE_ADDR','QUERY_STRING'));
		if ($pwServer['HTTP_X_FORWARDED_FOR'] && $pwServer['REMOTE_ADDR']) {
			if (strstr($pwServer['HTTP_X_FORWARDED_FOR'], ',')) {
				$x = explode(',', $pwServer['HTTP_X_FORWARDED_FOR']);
				$pwServer['HTTP_X_FORWARDED_FOR'] = trim($x[0]);
			}
			if (preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $pwServer['HTTP_X_FORWARDED_FOR'])) {return $pwServer['HTTP_X_FORWARDED_FOR'];}
		} elseif ($pwServer['HTTP_CLIENT_IP'] && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $pwServer['HTTP_CLIENT_IP'])) {return $pwServer['HTTP_CLIENT_IP'];}
		if (preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $pwServer['REMOTE_ADDR'])) {return $pwServer['REMOTE_ADDR'];}
		return 'Unknown';
	}
	
	public function PwdCode($pwd) {
		return md5(self::getServer('HTTP_USER_AGENT'). $pwd .Yii::app()->params['siteHash']);
	}
	/**
	 *  写活动参与记录
	 *  daxiu
	 * @param $acttype string act type
	 * 如果传有$mobile,则以$mobile 代替$uid
	 *  2011-08-04
	 */
	public static function mzone_set_act_join_list($acttype){
		$uid = self::getUid();
		$sql = "select id from act_join_list where uid='".$uid."' and acttype='".$acttype."'";
		$command = Yii::app()->db->createCommand($sql);
		$res = $command->queryRow();
		if(!$res){
			$time = date('Y-m-d H:i:s');
			$sql = "insert into  act_join_list(uid,mobile,acttype,jointime) values('".$_SESSION['mzone_winduid']."','".$_SESSION['mobile']."','".$acttype."','".$time."')";
			$command = Yii::app()->db->createCommand($sql);
			$command->query();
		}
	}
	
	/**
	 * 获取用户好友关系 
	 * zwy 2012-04-16
	 */
	public static function mzone_get_member_friends($uid,$order = 'joindate desc',$limit = '30,0'){
		$res = Yii::app()->mzone
			->createCommand()
			->select ("a.friendid, b.username,b.icon as face")
			->from ('pw_friends as a,pw_members as b')
			->where('a.friendid = b.uid and a.status=0 and a.uid=:uid',array(':uid'=>$uid))
			->order($order)
			->limit($limit)
			->queryAll();				
		return $res;
	}
	
	/**
	 * 显示用户头像
	 */
	public static function mzone_show_member_face($usericon,$show_a = null,$imgtype = null){
		!$imgtype && $imgtype = 'm';
		$user_a = explode('|',$usericon);
		if($user_a[1] == 1){
			$faceurl = '/images/face/'.$user_a[0];
		}elseif($user_a[1] == 2){
			$faceurl = $user_a[0];
		}elseif($user_a[1] == 3){
			$face = $user_a[0];
			$attachdir = Yii::app()->params['mzone_attachment_path'];
			list($imgtypedir,$ifUseThumb) = self::mzone_get_upload_typedir($imgtype,$user_a[5]);
			$old_user_a_0 = $user_a[0];
			if ($ifUseThumb == 1 && !$user_a[6] && strpos($user_a[0],'.') != false) {
				$user_a[0] = substr($user_a[0],0,strrpos($user_a[0],'.')+1).'jpg';
			}			
			if (file_exists("$attachdir/$imgtypedir/$user_a[0]")) {
				$faceurl = "/attachment/$imgtypedir/$user_a[0]";
			} elseif((strpos($imgtypedir,'middle') !== false || strpos($imgtypedir,'small') !== false) && file_exists("$attachdir/upload/$old_user_a_0")) {
				$faceurl = "/attachment/$imgtypedir/$old_user_a_0";
			} else {
				$faceurl = "/images/face/none.gif";
			}			
			//兼容旧版本 zwy 
			if($imgtype == 'm'){
				$faceurl = str_replace('_small','_big',$faceurl);
			}				
		}
		if(empty($faceurl)){
			$faceurl = "/images/face/none.gif";
		}
		return $faceurl;	
	}

	public static function mzone_get_upload_typedir($imgtype,$check){
		if (!$check) return array('upload','0');
		$imgtype = strtolower($imgtype);
		if ($imgtype == 'm') {
			return array('upload/middle','1');
		} elseif ($imgtype == 's') {
			return array('upload/small','1');
		} else {
			return array('upload','0');
		}
	}	
	
	
}







