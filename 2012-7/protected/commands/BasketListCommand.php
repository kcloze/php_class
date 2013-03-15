<?php
class BasketListCommand extends CConsoleCommand { 

	public function run($args){
		if(date('H')<23){
			echo 'you can run this script after 23 o`clock!';exit;
		}
		$date_s = date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y')));
		$date_e = date('Y-m-d',mktime(0,0,0,date('m'),date('d')+1,date('Y')));
		$sql="SELECT SUM(actType) AS msum,uid,mobile,username,postDate FROM act2012_shoot WHERE   postDate > '".$date_s."'  AND  postDate < '".$date_e."' GROUP BY uid ORDER BY msum DESC,postDate ASC LIMIT 100";
		$command=Yii::app()->db->createCommand($sql);
		$data=$command->queryAll();
		//var_dump($data);exit;
		
		    $i=1;
			foreach($data as $val){
				 
				if($i>100) break;
				if($val['msum']<=0) continue;
				//是否已经排过名,当天记录数大于50个，说明已经统计过排名
				$sql="SELECT count(*) as c FROM act2012_shoot_winners WHERE postDate = '".$date_s."' LIMIT 1";
				$command=Yii::app()->db->createCommand($sql);
				$isRankLimit=$command->queryScalar();
				if($isRankLimit>=50) break;
				//去掉话费奖励已经超过200元的uid
				$sql="SELECT COUNT(*) c FROM act2012_shoot_winners WHERE uid=".$val['uid'];
				$command=Yii::app()->db->createCommand($sql);
				$winerTimes=$command->queryScalar();
				if($winerTimes >=20) continue;
				
				//当天是否已经有中奖记录
				$sql="SELECT count(*) as c FROM act2012_shoot_winners WHERE uid=".$val['uid']." AND postDate = '".$date_s."' LIMIT 1";
				$command=Yii::app()->db->createCommand($sql);
				$isRank=$command->queryScalar();
				if($isRank) continue;
				
				$sql="INSERT INTO act2012_shoot_winners (uid,username,mobile,score,money,postDate,updateTime)VALUES(".$val['uid'].",'".$val['username']."','".$val['mobile']."',".$val['msum'].",10,'".$date_s."','".$val['postDate']."')";
				$command=Yii::app()->db->createCommand($sql);
				$winerTimes=$command->execute();
			
				$winerTimes && $i++;
				
				
				echo $val['uid'].'\n';
			}
			echo 'it is ok';
			
		
	}
	
	 public function getHelp() {  
        return 'test command help';  
    }  
}