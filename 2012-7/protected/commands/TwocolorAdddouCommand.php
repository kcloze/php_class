<?php
error_reporting(E_ALL & ~E_NOTICE);
class TwocolorAdddouCommand extends CConsoleCommand { 

	public function run($args){
        $datestr = date('Y-m-d');
        $hour = date('H');
		if($hour>23 or $hour < 10){
			echo 'you can not run this script between 10 and 23 o`clock!';exit;
		}
        $selhour = $hour-1; 
        //$datestr = '2012-06-06';
        //$selhour = 22;
        $conn=Yii::app()->db;
        $sql = "select username,mobile,uid,rewardmoney from twocolor_user_detail where date='".$datestr."' and hours=".$selhour.' and rewardmoney>0';
        //echo $sql;die();
        $command = $conn->createCommand($sql);
        $res = $command->queryAll();
        
        if($res){
            $credit = new MemberCredit();
            foreach($res as $r){
                //add豆豆
                $credit->addCredit( $r['uid'],$r['rewardmoney']);

                $credit->addCreditLog ('other_twocolor_tm_reward',$r['rewardmoney'],array('uid'=>$r['uid'],'username'=>$r['username'],'ip'=>'Unknow2'));
            }
           
        }
		echo 'it is ok';			
		
	}
	
	 public function getHelp() {  
        return 'test command help';  
    }  
}