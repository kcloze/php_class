<?php
class TwocolorCommand extends CConsoleCommand { 

	public function run($args){
		if(date('H')<23){
			//echo 'you can run this script before 23 o`clock!';exit;
		}
        $datestr = date('Y-m-d',time()-24*60*60);
        $conn=Yii::app()->db;
        $sql = "select sum(rewardmoney) as count,username,mobile,uid from twocolor_user_detail where date='".$datestr."' group by uid having count > 0 order by count desc,datetime asc limit 0,85";
        //echo $sql;die();
        $command = $conn->createCommand($sql);
        $res = $command->queryAll();
        
        if($res){
            $idArr = array();
            $i = 1;
            $k = 0; //门票名额 ==5个
            foreach($res as $r){
                //门票名额够5个之后跳出
                if($i >= 16 and $k > 4)
                     break;
                /*
                $sql = "SELECT count(*) as c FROM twocolor_user_prize WHERE uid='".$r['uid']."'";
                $one = $db->get_one($sql); 
                if(!$one or $one['c'] <= 270){
                    $idlist .= $r['id'].',';
                    $idArr[] = $r;
                    $i++;
                }*/
                $sql = "SELECT count(*) c FROM twocolor_user_prize WHERE uid='".$r['uid']."' and isticket=1";
                unset($command);
                $command = $conn->createCommand($sql);
                $one = $command->queryRow();
                
                $r['isticket'] = 0;
                //整个活动每个用户最多得2张门票。
                if((!$one or $one['c'] < 2) and $k<5){                    
                    $r['isticket'] = 1;
                    ++$k;
                }
                $idArr[] = $r;
                $i++;
            }
            //前15名，入库中奖详情表   
            $value = "insert into twocolor_user_prize(uid,mobile,username,date,createtime,count,isticket) values";
            foreach($idArr as $r){
                $value .= "(".$r['uid'].",'".$r['mobile']."','".$r['username']."','".$datestr."','".date('Y-m-d H:i:s')."',".$r['count'].",".$r['isticket']."),";
            }
            $value = substr($value,0,-1);
            unset($command);
            $command= $conn->createCommand($value);
            $command->query();
            
        }
		echo 'it is ok';			
		
	}
	
	 public function getHelp() {  
        return 'test command help';  
    }  
}