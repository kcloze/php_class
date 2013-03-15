<?php
/**
 * 问卷调查
 */
class DCHelper{
	/**
	 * $num 题数目
	 * $arr 多选的题目序号,例如 ：array(1,4,7)
	**/
	public function getDCAnswer($num,$arr=array()){
		$answerData = '';
		for($i = 1;$i<=$num;$i++){
			$answer = 'RadioGroup'.$i;
			$tmpdata = $_POST[$answer];
			if($arr && in_array($i,$arr)){
				$vdata = implode(',', $tmpdata);
				$answerData .= $vdata .'|';
			}else{
			   	$answerData .= str_replace('|', '｜', $tmpdata) . '|';
			}			
		}
		$tempstr = rtrim($answerData, "|");
		return $answerData;
	} 
	
	/**
	 * 问卷调查标志（名称）
	 * $num 题数目
	 * $arr 多选的题目序号,
	 * 例如：array(3=>array(1,4,7),2=>array(8))表示第1、4、7题最多可以选三项,8题最多可以选二项
	 * $empty 可以为空的题目序号,参数'source_num'、'value'可以为空，'empty_num'不能为空
	 * 例如：array(array('source_num'=>1,'value'=>'daxiu','empty_num'=>2))表示第1题的答案为'daxiu'时，第2题可以为空；
	 *  $empty 算法还有问题
	 */
	public function checkDCData($step = 'dc01',$num,$arr=array(),$empty=array()){
		if(MemberHelper::isGuest()){
			return '很抱歉，您现在是未登录状态不能答题哦。';
		}
		//是否已参与过
		if(self::isDC($step)){
			return '很抱歉，您已经回答过了，一人只能参与一次哦。';
		}
		
		for($i = 1;$i<=$num;$i++){
		   $answer = 'RadioGroup'.$i;
			if(empty($_POST[$answer])){
				if(!empty($empty)){
					foreach($empty as $em){
						if($em['source_num'] == '')
							continue;
						else{
							if($i == $em['empty_num'] && $_POST['RadioGroup'.$em['source_num']] == $em['value'])
								continue;
							else
								return '对不起，请您检查第'.$i .'题！';
						}
					}
				}else
					return '对不起，请您检查第'.$i .'题！';
			}	   
		}
		if($arr){
			foreach($arr as $k=>$tmp){
				foreach($tmp as $a){
					$answer = 'RadioGroup'.$a;
					if(count($_POST[$answer])>$k){
						return '对不起，第'.$a.'题最多只能选'.$k.'项！';
					}					
				}
			}
		}
		return false;
	}
	
	// 检查是否已答题
	public function isDC($step){
		$uid = MemberHelper::getUid();
		$sql = "select count(*) c from dc where uid='$uid' and step='$step'";
		$command = Yii::app()->db->createCommand($sql);
		$rows=$command->queryScalar();
		if($rows){
            return $rows;
        }
        return 0;
	}
	
	
	//保存数据
	public function saveDCData($profile,$data){
		if(MemberHelper::isGuest()){
			return false;
		}
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO dc (uid,truename,mobile,step,answer,date) values(:uid,:truename,:mobile,:step,:answer,:date)";
		$command = Yii::app()->db->createCommand($sql);
	
	    $command->bindParam(":uid",$profile['uid'],PDO::PARAM_STR);
	 	$command->bindParam(":truename",$profile['username'],PDO::PARAM_STR);
	    $command->bindParam(":mobile",$profile['authmobile'],PDO::PARAM_STR);
	    $command->bindParam(":step",$data['step'],PDO::PARAM_STR);
	    $command->bindParam(":answer",$data['answer'],PDO::PARAM_STR);
	    $command->bindParam(":date",$date,PDO::PARAM_STR);  
	    $rows=$command->execute();   		
	}
	
	//添加动感豆豆
	public function addAward($userinfo,$step='dc01',$money=100){
		$upSqlCredit="UPDATE  pw_memberdata SET money=money+$money WHERE uid=".$userinfo['uid'];
		$command = Yii::app()->pwind->createCommand($upSqlCredit);
		$command->execute();
		
		$sql_credit_log="INSERT INTO pw_creditlog (uid, username, ctype, affect, `adddate`, logtype, ip, descrip) values (".$userinfo['uid'].",'".$userinfo['username']."','money',$money,'".time()."','other_dc01_doudou','".CommonHelper::getIp()."','参与2012问卷调查{$step}奖励{$money}动感豆豆')";
		$command = Yii::app()->pwind->createCommand($sql_credit_log);
		$command->execute();
	}
	
	

	
	
}