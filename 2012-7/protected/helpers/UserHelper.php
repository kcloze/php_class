<?php
class UserHelper{
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
		list($actuid) = explode("\t",addslashes(self::strCode(self::getCookie('winduser'),'DECODE')));
		$actuid = is_numeric($actuid)?$actuid:''; 
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
		$sql = 'SELECT m.uid,m.username as truename,m.authmobile as mobile,m.email,oicq,m.groupid,
				m.memberid,m.groups,m.icon,m.regdate,m.newpm,m.userstatus,m.shortcut,md.postnum,md.money,
				md.lastvisit,md.thisvisit,md.onlinetime,md.digests 
				FROM pw_members m 
				LEFT JOIN pw_memberdata md ON m.uid=md.uid 
				WHERE m.uid=:uid';	
		$command = Yii::app()->pwind->createCommand($sql);
		$command->bindParam(':uid', $actuid, PDO::PARAM_STR);
		$res = $command->queryRow();
		if(empty($res)) return $res;
		$res['hideMobile'] = (strlen($res['mobile']) == 11) ? substr_replace($res['mobile'],'****',6,4) : $res['mobile'];
		$res['faceurl'] = self::getFaceUrl($res['icon']);
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
        return $userinfo['truename'];
    }
		
	/**
	 * 获取用户站内信数量
	 */
	public static function getMsgCount($uid=''){
		$actuid = empty($uid) ? self::getUid() : $uid;
	    $msgcount = 0;
	    $ifnew = 1;
	    $type = 'rebox';
     	$sql = 'SELECT COUNT(*) AS msgcount FROM pw_msg WHERE ifnew=:ifnew AND touid=:touid and type=:type';
     	$command = Yii::app()->pwind->createCommand($sql);
		$command->bindParam(":ifnew",$ifnew,PDO::PARAM_STR);
		$command->bindParam(':type',$type,PDO::PARAM_STR);
	    $command->bindParam(':touid', $actuid, PDO::PARAM_STR);
	    $msg = $command->queryColumn();
	    $msgcount = empty($msg[0]) ? 0 :$msg[0];
		return $msgcount;
	}	
	/**
	 * 获取用户手机品牌信息
	 */
	public static function getBoss()
	{
		$actuid = empty($uid) ? self::getUid() : $uid;
		$sql = 'SELECT mobile,type,area,start_time FROM pw_user_boss_data WHERE uid=:uid';
		$command = Yii::app()->pwind->createCommand($sql);
		$command->bindParam(':uid', $actuid, PDO::PARAM_STR);
		$res = $command->queryRow();
		return $res;
	}
	/**
	 * 获取用户头像 
	 */
	public static function getFaceUrl($usericon,$show_a = null) {
		$user_a = explode('|',$usericon);
		if(empty($user_a[0]))
			return Yii::app()->params['siteMedia'].'bbs/images/face/none.gif';
		else
			return Yii::app()->params['siteMedia'].$user_a[0];
		
	}
	
	public static function getLoginHash(){
		$onlineip = self::getOnLineIP();
		return self::getVerify($onlineip,Yii::app()->params['db_pptkey']);
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
		$pre = substr ( md5 ( Yii::app()->params['siteHashPwind'] ), 0, 5 );
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
		/*
		if ($pwServer['HTTP_X_FORWARDED_FOR'] && preg_match('/^(([0-9]{1,3}\.){3}[0-9]{1,3}\s*,?\s*)*$/', $pwServer['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode( ',', $pwServer['HTTP_X_FORWARDED_FOR'] );
			$onlineip = trim( $arr[0] );
		}
		
		if ($onlineip == 'Unknown' && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/',$pwServer['REMOTE_ADDR'])) {
			$onlineip = $pwServer['REMOTE_ADDR'];
		}
		return $onlineip;
		*/
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
	
	/**
	 * 
	 * 获取用户当前boss 数据
	 */
	public static function getBossNow(){
		
		$mc = new MzoneCaller();
		$mc->set_url( 'activity_do.php?act=boss&action=get' );
		$mc->add_post( array( 'sid'=> session_id() ));
		$rs = $mc->do_request();

		var_dump( $rs );die;
		!empty( $rs ) && $rs = unserialize($rs);
		
		var_dump( $rs );die;
	}

	/**
	*  写活动参与记录
	*  daxiu
	* @param $acttype string act type
	* 如果传有$mobile,则以$mobile 代替$uid
	*  2011-08-04
	*/
	public static function mzone_set_act_join_list($acttype,$mobile=''){
		if(!$mobile){
			$uid = self::getUid();
			$sql = "select id from act_join_list where uid='".$uid."' and acttype='".$acttype."'";
		}else{
			$sql = "select id from act_join_list where mobile='".$mobile."' and acttype='".$acttype."'";
		}
		$command = Yii::app()->db->createCommand($sql);
		$res = $command->queryRow();
		if(!$res){
			if(!$mobile){
				$user = self::getProfile();
			}
			$time = date('Y-m-d H:i:s');
			$sql = "insert into  act_join_list(uid,mobile,acttype,jointime) values('".($mobile?substr($mobile,7):$user['uid'])."','".($mobile?$mobile:$user['mobile'])."','".$acttype."','".$time."')";
			$command = Yii::app()->db->createCommand($sql);
			$command->query();
		}
	}
	
	/**
	 * 获得活动参与人数 
	 */
	public static function mzone_get_act_join_list($acttype){
		$sql = "select count(*) c from act_join_list where acttype='".$acttype."'";
		$command = Yii::app()->db->createCommand($sql);
		$res = $command->queryScalar();
		return $res;
	}
	
	/*
	 * 自动发帖
	 * uid 用户id
	 * fid 所属版块
	 * type 分类
	 * tile 标题
	 * content 内容
	 * ifcheck 1：审核   0：不审核
	 */
	public static function mzone_auto_bbs($uid,$fid,$type,$title,$content,$ifcheck=1){
		$sql="SELECT m.uid,m.username,m.password,m.safecv,m.email,m.bday,m.oicq,m.groupid,m.memberid,m.groups,m.icon,m.regdate,m.honor,m.timedf,m.style,m.datefm,m.t_num,m.p_num,m.yz,m.newpm,m.userstatus,m.shortcut,m.medals,m.gender,md.lastmsg,md.postnum,md.rvrc,md.money,md.credit,md.currency,md.lastvisit,md.thisvisit,md.onlinetime,md.lastpost,md.todaypost,md.monthpost,md.onlineip,md.uploadtime,md.uploadnum,md.starttime,md.pwdctime,md.monoltime,md.digests,md.f_num,md.creditpop,md.jobnum,md.lastgrab,md.follows,md.fans,md.newfans,md.newreferto,md.newcomment,md.punch,md.bubble,md.newnotice,md.newrequest,md.shafa ,md.postcheck,sr.visit,sr.post,sr.reply FROM pw_members m
		LEFT JOIN pw_memberdata md ON m.uid=md.uid LEFT JOIN pw_singleright sr ON m.uid=sr.uid WHERE m.uid= '".$uid."'  AND m.groupid<>'0' AND md.uid IS NOT NULL";
		$command = Yii::app()->mzone->createCommand($sql);
		$res = $command->queryRow();
		
		if($res){
			$sql="INSERT INTO  `pw_threads`   SET   `fid`  =  '".$fid."' ,  `icon`  =  '0' ,  `author`  =  '".$res['username']."' ,  `authorid`  =  '".$uid."' ,  `subject`  =  '".$title."' ,  `ifcheck`  =  '".$ifcheck."' ,  `type`  =  '".$type."' ,  `postdate`  =  '".time()."' ,  `lastpost`  =  '".time()."' ,  `lastposter`  =  '".$res['username']."' ,  `hits`  =  '1' ,  `replies`  =  '0' ,  `topped`  =  '0' ,  `digest`  =  '0' ,  `special`  =  '0' ,  `state`  =  '0' ,  `ifupload`  =  '0' ,  `ifmail`  =  '0' ,  `anonymous`  =  '0' ,  `ptable`  =  '' ,  `ifmagic`  =  '0' ,  `ifhide`  =  '0' ,  `tpcstatus`  =  '0' ,  `modelid`  =  '0' ,  `frommob`  =  ''";

			$command = Yii::app()->mzone->createCommand($sql);
			$command->query();
			
			$tid=Yii::app()->mzone->getLastInsertId();
			$ip=Yii::app()->request->userHostAddress;
			$sql="INSERT INTO pw_tmsgs  SET   `tid` ='".$tid."',  `aid` ='0',  `userip` ='".$ip."',  `ifsign` ='2',  `buy` ='',  `ipfrom` ='广州',  `tags` ='	',  `ifconvert` ='1',  `ifwordsfb` ='1',  `content` ='".$content."',  `magic` =''";
			$command = Yii::app()->mzone->createCommand($sql);
			$command->query();
			
			$postnum=$res['postnum']+1;
			$todaypost=$res['todaypost']+1;
			$monthpost=$res['monthpost']+1;
			$sql="UPDATE  `pw_memberdata`   SET   `postnum`  =  '".$postnum."' ,  `todaypost`  =  '".$todaypost."' ,  `monthpost`  =  '".$monthpost."' ,  `lastpost`  =  '".time()."' ,  `uploadtime`  =  '0' ,  `uploadnum`  =  '0'  WHERE uid= '".$uid."'";
			$command = Yii::app()->mzone->createCommand($sql);
			$command->query();
            return $tid;
		}
        return 0;
	}
     /**
     * 读取用户购买道具的数据
     * 
     * @param mixed $toolid
     * @return mixed
     */
     public static function  mzone_get_tool_byid($uid,$toolid){
        if(!uid || !$toolid) return false;
        $sql = "select * from `dedev51sp1pw`.pw_usertool where uid=".$uid." and toolid=".$toolid;
        $command = Yii::app()->pwind->createCommand($sql);
        $res=$command->queryRow();
        return $res;
    }
    /**
    * 查询道具数量
    * 
    * @param mixed $toolid
    * @return mixed
    */
    public static function mzone_check_tool_num($toolid){
       $sql = "select stock from `dedev51sp1pw`.pw_tools where id=".$toolid;
       $command = Yii::app()->pwind->createCommand($sql);
       $res=$command->queryRow();
       return $res['stock']; 
    }
    /**
    * 更新道具数量
    * 
    * @param mixed $toolid
    */
    public static function mzone_update_tool_num($toolid,$num){
        $sql = "update `dedev51sp1pw`.pw_tools set stock = stock-".$num." where id=".$toolid;
        $command = Yii::app()->pwind->createCommand($sql);
        $command->query();
    }
    /**
    * 购买道具 insert
    * 
    * @param mixed $valuestr
    */
    public static function 	mzone_insert_tool($valuestr){
        if(!$valuestr) return false;
        $sql = "insert into `dedev51sp1pw`.pw_usertool(uid,tstate,toolid,nums,sellnums,stype,sellprice,buydate,usedate,enddate) values(".$valuestr.")";
        $command = Yii::app()->pwind->createCommand($sql);
        if($command->query())
            return true;
        else
            return false;
    }	
    /**
    * 购买道具 update
    * 
    * @param mixed $value
    */
    public static function  mzone_update_tool($value = array()){
        if(!$value) return false;
        $sql = "update `dedev51sp1pw`.pw_usertool set ".$value['set']." where ".$value['where'];
        $command = Yii::app()->pwind->createCommand($sql);
        if($command->query())
            return true;
        else
            return false;
    }
    /**
    * 道具购买日志
    * 
    * @param mixed $valuestr
    */
    public static function mzone_set_buytool_log($valuestr){
        if(!$valuestr) return false;
        $sql = "insert into `dedev51sp1pw`.pw_toollog(type,nums,money,descrip,uid,username,ip,time,filename,touid) values(".$valuestr.")";
        $command = Yii::app()->pwind->createCommand($sql);
        if($command->query())
            return true;
        else
            return false;
    }
}







