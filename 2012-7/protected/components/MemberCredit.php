<?php
/**
 *  @pwind简化的积分操作
 *  @zwy 2012-04-06
 */

class MemberCredit{

	var $creditType = array();	//积分名称 array('money' => ??, 'rvrc' => ??, ...)
	var $cUnit = array();	//积分单位 array('money' => ??, 'rvrc' => ??, ...)
	
	//注：自定义积分需手动添加 数字的即为自定义积分 	
	function __construct(){
		$this->creditType = array(
			'money'		=> '动感豆豆',
			'rvrc'		=> '威望',
			'credit'	=> '贡献值',
			'currency'	=> '银元'
		);
		$this->cUnit = array(
			'money'		=> '个',
			'rvrc'		=> '点',
			'credit'	=> '点',
			'currency'	=> '个'
		);
		$_CREDITDB=array(
			'1' => array(
				'0' => '好评度',
				'1' => '点',
				'2' => '自定义积分',
			),
			'2' => array(
				'0' => '新生总动员积分',
				'1' => '点',
				'2' => '2011新生总动员所用积分',
			),
			'3' => array(
				'0' => '欢乐派对热情指数',
				'1' => '点',
				'2' => '欢乐派对热情指数',
			),
		);	
		
		foreach ($_CREDITDB as $key => $value) {
			$this->creditType[$key] = $value[0];
			$this->cUnit[$key] = $value[1];
		}
	}
	
	
	//加积分
	public function addCredit($uid,$point,$cType='money'){
		if($point <> 0 ){
			$point = intval($point);
			if (is_numeric($cType) && isset($this->creditType[$cType])) {
				$creditdata = Yii::app()->mzone->createCommand()
				->select('*')
				->from('pw_membercredit')
				->where('uid=:uid and cid=:cid', array(':uid'=>$uid, ':cid'=>$cType))
				->queryRow();
				if(empty($creditdata)){
					Yii::app()->mzone->createCommand()
					->insert('pw_membercredit',array('uid'=>$uid,'value'=>$point,'cid'=>$cType));
				}else{
					Yii::app()->mzone->createCommand()
					->update('pw_membercredit',array(
					value =>new CDbExpression("value+$point")
					),'uid=:uid', array(':uid'=>$uid));
				}
				
			}elseif(isset($this->creditType[$cType])){
				Yii::app()->mzone->createCommand()
				->update('pw_memberdata',array(
					$cType =>new CDbExpression("$cType+$point")
				),'uid=:uid', array(':uid'=>$uid));
			}			
		}

	} 
	
	//查看积分
	public function getCredit($uid,$cType='money'){
		if (is_numeric($cType) && isset($this->creditType[$cType])) {
			$getv = Yii::app()->mzone->createCommand()
		    ->select('value')
		    ->from('pw_membercredit')
		    ->where('uid=:uid and cid=:cid', array(':uid'=>$uid,':cid'=>$cType))
		    ->queryScalar();
		
		}elseif(isset($this->creditType[$cType])){
			$getv = Yii::app()->mzone->createCommand()
		    ->select($cType)
		    ->from('pw_memberdata')
		    ->where('uid=:uid', array(':uid'=>$uid))
		    ->queryScalar();
		}
		empty($getv) && $getv = 0;
		return $getv;
	}
	
	//加日志
	public function addCreditLog($logtype,$cost,$L,$cType='money'){	
		require(dirname(__FILE__).'/../config/lang_creditlog.php');

		$cost > 0 && $cost = '+'.$cost;
		$L['affect'] = $cost;
		$L['cname'] = $this->creditType[$cType];	
        if(empty($L['ip'])){
            $L['ip'] = CommonHelper::getIp();
        }
        $descrip = '';
		empty($L['ip']) && $L['ip'] = 'Unknow';	
		if (isset($lang['creditlog'][$logtype])) {
			eval('$descrip="' . addcslashes($lang['creditlog'][$logtype], '"') . '";');
		}
		Yii::app()->mzone->createCommand()
					->insert('pw_creditlog',array('uid'=>$L['uid'],
						'username'=>$L['username'],
						'ctype'=>$cType,
						'affect'=>$cost,
						'adddate'=>time(),
						'logtype'=>$logtype,
						'ip'=>$L['ip'],
						'descrip'=>$descrip,
					));
	}
}

