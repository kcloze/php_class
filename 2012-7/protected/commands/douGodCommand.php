<?php
error_reporting(E_ALL & ~E_NOTICE);
class DouGodCommand extends CConsoleCommand { 

	public function run($args){
        $datestr = date('Y-m-d');
        $hour = date('H');
        //$datestr = '2012-06-10';
		if($hour<21){
			//echo 'you can not run this script before 21 o\'oclock !';exit;
		}
        $conn=Yii::app()->db;
        //总参与人数
        $sql = "select count(*) c from sendlucky_user_detail where date='".$datestr."'";       
        $command = $conn->createCommand($sql);
        $allres = $command->queryRow();
        /**/
        //最小参与记录id      
        $sql = "select min(id) minid from sendlucky_user_detail where date='".$datestr."'";
        unset($command);
        $command = $conn->createCommand($sql);
        $minres = $command->queryRow();
        $minid = $minres['minid'];
        //最大参与记录id
        $sql = "select max(id) maxid from sendlucky_user_detail where date='".$datestr."'";
        unset($command);
        $command = $conn->createCommand($sql);
        $maxres = $command->queryRow();
        $maxid = $maxres['maxid'];
        
        //默认+10个豆豆
        $onedou = 10;
        if($allres['c'] <=5){
            //小于等于5条记录时
            $sql = "select id,uid,username,mobile from sendlucky_user_detail where date='".$datestr."'";            
            unset($command);
            $command = $conn->createCommand($sql);
            $res = $command->queryAll();
        }else{
            $onedou = ($allres['c'] * 10)/5;
            $randArr = array();
            
            $sql ="select d.id,d.uid,d.username,d.mobile from sendlucky_user_detail d 
                    left join special_members s on s.uid = d.uid 
                    where d.date='".$datestr."' and s.uid is NULL";
            $command = $conn->createCommand($sql);
            $res = $command->queryAll();
            $maxnum = count($res);
            $idlist = '';
            if($maxnum < 5){
                foreach($res as $r){
                    $randArr[] = $r['id'];
                }
                while(1){
                  $tmp = rand($minid,$maxid);
                    if(!in_array($tmp,$randArr)){
                        array_push($randArr,$tmp);
                    }
                    if(count($randArr) >= 5)
                        break; 
                }
            }else{          
                $minid = 0;
                $maxid = $maxnum-1;
                while(1){
                    $tmp = rand($minid,$maxid);
                    if(!in_array($tmp,$randArr)){
                        array_push($randArr,$tmp);
                    }
                    if(count($randArr) >= 5)
                        break;
                }                
            }
            foreach($randArr as $ra){
                $idlist .= $ra.',';
            }
            $idlist = substr($idlist,0,-1);
            $sql = "select id,uid,username,mobile from sendlucky_user_detail where id in(".$idlist.")";//echo $sql;die('test');
            unset($command);
            $command = $conn->createCommand($sql);
            $res = $command->queryAll();            
        }
        //加豆豆
        foreach($res as $r){
            //$ip = CommonHelper::getIp();
            Yii::app()->db->createCommand()
                ->insert('songdou_user_detail', array(
                        'uid'=>$r['uid'],
                         'username'=>$r['username'],
                        'mobile'=>$r['mobile'],
                        'adddate'=>time(),
                        'reward'=>$onedou,
                        'logtype'=>'sendlucky',
                        'ip'=>'sendlucky'
                    ));           
            $this->mzone_adddou_fromgod($r,$onedou);
        }
         
        
           
        
		echo 'it is ok';			
		
	}
	public function mzone_adddou_fromgod($r,$rewardmoney=10){
        //add豆豆
        $credit = new MemberCredit();

        $credit->addCredit( $r['uid'],$rewardmoney);

        $credit->addCreditLog ('other_sendlucky_tm',$rewardmoney,array('uid'=>$r['uid'],'username'=>$r['username'],'ip'=>'sendlucky'));
    }
	 public function getHelp() {  
        return 'test command help';  
    }  
}
