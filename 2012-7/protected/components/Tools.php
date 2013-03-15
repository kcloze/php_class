<?php
/**
 * 道具处理类
 * @author mc
 *
 */

class Tools {

	private $tool_id;
	private $proc;
	
	/**
	 * @var SmartModel
	 */
	private $toolinfo;
	
	/**
	 * SmartModel 
	 * @var SmartModel
	 */
	private $smodel;
	
	
	public function __construct( $tool_id = 0, $proc = true ){
		
		// 如果非空，则进行单个道具处理 
		$this->tool_id = $tool_id;
		$this->proc = $proc;
		$this->smodel = SmartModel::model( 'pw_tools' );
		
		if( !empty( $tool_id ) ){
			$this->toolinfo = $this->getToolInfo();		
		}
	}

	/**
	 * 获取推荐列表
	 * @return array
	 */
	public static function getRecommendList(){
		
		$smodel = SmartModel::model( 'pw_tools' );
		
		$c = new CDbCriteria();
		$c->condition = 'state=:state';
		$c->params = array(':state'=>1);
		
		$p = new CPagination( $smodel->count( $c ) );
		$p->setPageSize( 10 );
		$p->applyLimit($c);
		
		return $smodel->findAll( $c );
	}
	
	/**
	 * 
	 * 购买时需要检测，用户已经拥有则不需要再购买。
	 */
	public function checkToPeople( $uid ){
		
		$ttype = $this->toolinfo->ttype;
		if($ttype == 1){
			// 每款道具每人限购一次，过期后可继续购买
			
			$c = new CDbCriteria();
			$c->condition = 'uid=:uid';
			$c->params = array(':uid'=>$uid);
			
			$sm = SmartModel::model('pw_usertool');
			if( $usertoolinfo = $sm->find( $c ) ){
				return false;
			}
		}
		return true;
	}
	
	public function doBuy(){
		
		$credit = Mzone::app()->credit;
	
		$credit->addLog('hack_toolbuy',array($toolinfo['creditype'] => -$price),array(
			'uid'		=> $winduid,
			'username'	=> $windid,
			'ip'		=> $onlineip,
			'nums'		=> $nums,
			'toolname'	=> $toolinfo['name']
		));
		$credit->set($winduid,$toolinfo['creditype'],-$price);
			
	}
	
	public function saveToPeople( $nums ){
		
		empty($nums) && $nums = 1;
		$toolinfo = $this->toolinfo;
		$winduid = Mzone::app()->user->winduid;
		$today = Mzone::app()->today;
		$tool_id = $this->tool_id;
		$db = Yii::app()->pwind;
		
		if ( $this->procLock('tool_save',$winduid) or !$this->proc ) {

			$db->update( "UPDATE pw_tools SET stock=stock-".pwEscape($nums)."WHERE id=".pwEscape($this->tool_id) );
			
			// 记录用户购买
			$today = date( 'Y-m-d' );
			$arr = array('nums'=>$nums,'uid'=>$winduid,'toolid'=>$tool_id,'buydate'=>$today,'tstate'=>0 );
			if( 1 == $toolinfo['ttype'] ){
				$arr = array_merge( $arr, array( 'usedate'=>$today ) );
				$enddate = date( 'Y-m-d', strtotime( "+$toolinfo[tool_validate] month, +$toolinfo[tool_validate_date] day" ) );
				$arr = array_merge( $arr, array( 'enddate'=>$enddate ) );
			}
			$db->update("INSERT INTO pw_usertool SET ".pwSqlSingle( $arr ));
			
			//fclose($fp);
			$logdata = array(
				'type'		=>	'buy',
				'nums'		=>	$nums,
				'money'		=>	$price,
				'descrip'	=>	'buy_descrip',
				'uid'		=>	$winduid,
				'username'	=>	Mzone::app()->user->winddb['truename'],
				'ip'		=>	Mzone::app()->onlineip,
				'time'		=>	Mzone::app()->timestamp,
				'toolname'	=>	$toolinfo['name'],
				'from'		=>	'',
			);
			writetoollog($logdata);
			$this->proc && $this->procUnLock( 'tool_save',$winduid );
		}
	}
	
	/**
	 * 物品保存时不可使用
	 */
	public function procLock( $t = '', $u = 0 ){
		
		$timestamp = Mzone::app()->timestamp;
		
		if (Yii::app()->pwind->query("INSERT INTO pw_proclock (uid,action,time) VALUES ('$u','$t','$timestamp')",'U',false)) {
			return true;
		}
		Yii::app()->pwind->update("DELETE FROM pw_proclock WHERE uid='$u' AND action='$t' AND time < '$timestamp' - 30");
		return false;
	}
	
	/**
	 * 物品保存时不可使用
	 */
	public function procUnlock( $t = '', $u = 0 ){
		Yii::app()->pwind->update("DELETE FROM pw_proclock WHERE uid='$u' AND action='$t'");
	}
	
	/**
	 * 
	 * @return SmartModel
	 */	
	public function getToolInfo(){
	
		if( empty( $this->tool_id ) ){
			return false;
		}
		return $this->smodel->findByAttributes( array('id'=>$this->tool_id) ); 
	}

	/**
	 * 通过标记寻找道具
	 * @param unknown_type $tflag
	 */
	public static function getToolByTflag( $tflag ){
		
		$sql = "select * from pw_tools where tflag = ". pwEscape( $tflag );
		return Mzone::app()->db->fetch_result($sql);
	}
	
}  



