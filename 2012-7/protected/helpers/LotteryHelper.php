<?php
class LotteryHelper{
    public static function getWinUserList($lotteryactivityId, $lottery, $limit = '0, 30',$order='lotterysms.id desc',$pageSize='20'){
        if(!is_array($lottery)) return array();
		$orderby = ' order by '.$order;
        $connection = Yii::app()->db;
        $be_first = $lottery[0];
        $be_last = $lottery[count($lottery)-1];
        $sql = 'select count(1) cnt from lotterysms inner join lottery on lotterysms.lotteryId=lottery.id 
        where lottery.lotteryactivityId=:lotteryactivityId 
        and lotterysms.lotteryId between :be_first and :be_last and lotterysms.toPhone is not null';
        
        $command = $connection->createCommand($sql);
        $command->bindParam(":lotteryactivityId",$lotteryactivityId,PDO::PARAM_STR);
        $command->bindParam(":be_first",$be_first,PDO::PARAM_STR);
        $command->bindParam(":be_last",$be_last,PDO::PARAM_STR);
                
		$cnt_tmp = $command->query();
	
		if( !$one = $cnt_tmp->read() ){
			throw new CHttpException(400, Yii::t('AdminModule.Appbox', 'data error') );
		}
		$cnt = $one['cnt'];

		$pages = new CPagination ( $cnt );
		$pages->pageSize = $pageSize;
		$pages->pageVar="p";
		$pages->setCurrentPage(intval($_GET['p']));
        $sql = 'select lotterysms.lotteryId,lotterysms.username, lotterysms.editTime, lottery.name as `lottery_name` from lotterysms inner join lottery on lotterysms.lotteryId=lottery.id 
        		where lottery.lotteryactivityId=:lotteryactivityId and lotterysms.lotteryId 
        		between :be_first and :be_last and lotterysms.toPhone is not null '. $orderby.' limit '.$pages->getOffset().','. $pages->getLimit();
        //.' LIMIT :offset,:limit ';
		//return $sql;
        $command = $connection->createCommand($sql);
        $command->bindParam(":lotteryactivityId",$lotteryactivityId,PDO::PARAM_STR);
        $command->bindParam(":be_first",$be_first,PDO::PARAM_STR);
        $command->bindParam(":be_last",$be_last,PDO::PARAM_STR);
        
        
        $rows=$command->queryAll(); 
        if($rows == null){
            return array();
        }
        return $rows;
    }
    
    public static function getWinUserListWithIn($lotteryactivityId, $lottery, $limit = '0, 30',$order='lotterysms.id desc',$pageSize='20'){
    	if(!is_array($lottery)) return array();
    	$orderby = ' order by '.$order;
    	$connection = Yii::app()->db;
    	
    	if(empyt($lottery)){
    		echo '$lottery 不能为空';
    	    exit;
    	}
    	$inString=' ( '.join(",", $lottery).' ) ';
    	$sql = 'select count(1) cnt from lotterysms inner join lottery on lotterysms.lotteryId=lottery.id
    	where lottery.lotteryactivityId=:lotteryactivityId
    	and lotterysms.lotteryId IN '.$inString.' and lotterysms.toPhone is not null';
    
    	$command = $connection->createCommand($sql);
    	$command->bindParam(":lotteryactivityId",$lotteryactivityId,PDO::PARAM_STR);
    	
    
    	$cnt_tmp = $command->query();
    
    	if( !$one = $cnt_tmp->read() ){
    		throw new CHttpException(400, Yii::t('AdminModule.Appbox', 'data error') );
    	}
    	$cnt = $one['cnt'];
    
    	$pages = new CPagination ( $cnt );
    	$pages->pageSize = $pageSize;
    	$pages->pageVar="p";
    	$pages->setCurrentPage(intval($_GET['p']));
    	$sql = 'select lotterysms.lotteryId,lotterysms.username, lotterysms.editTime, lottery.name as `lottery_name` from lotterysms inner join lottery on lotterysms.lotteryId=lottery.id
    	where lottery.lotteryactivityId=:lotteryactivityId and lotterysms.lotteryId
    	IN '.$inString.' and lotterysms.toPhone is not null '. $orderby.' limit '.$pages->getOffset().','. $pages->getLimit();
    	//.' LIMIT :offset,:limit ';
    	//return $sql;
    	$command = $connection->createCommand($sql);
    	$command->bindParam(":lotteryactivityId",$lotteryactivityId,PDO::PARAM_STR);
    	
    
    
    	$rows=$command->queryAll();
    	if($rows == null){
    		return array();
    	}
    	return $rows;
    }
    public static function getWinUserCount($lotteryactivityId, $lottery){
        return 1000;
        if(!is_array($lottery)) return array();
        
        $connection = Yii::app()->db;
        $be_first = $lottery[0];
        $be_last = $lottery[count($lottery)-1];
        $sql = 'select count(1) from lotterysms inner join lottery on lotterysms.lotteryId=lottery.id where lottery.lotteryactivityId=:lotteryactivityId and lotterysms.lotteryId between :be_first and :be_last and lotterysms.toPhone is not null';
        //return $sql;
        $command = $connection->createCommand($sql);
        $command->bindParam(":lotteryactivityId",$lotteryactivityId,PDO::PARAM_STR);
        $command->bindParam(":be_first",$be_first,PDO::PARAM_STR);
        $command->bindParam(":be_last",$be_last,PDO::PARAM_STR);
        
        $rows=$command->queryScalar();
        if($rows){
            return $rows;
        }
        return 0;
    }  

    public static function getWinUserByMobile($mobile){

        $connection = Yii::app()->db;
        
        $sql = 'select lotterysms.*, lottery.name as `lottery_name` from lotterysms inner join lottery on lotterysms.lotteryId=lottery.id 
        where 

        toPhone=:mobile and lotteryId in(5,6,7,8,9) ';
//        $sql = 'select * from lotterysms where toPhone='. $mobile .' and lotteryId in(5,6,7,8) order by editTime ';
        //return $sql;
        $command = $connection->createCommand($sql);
        $command->bindParam(":mobile",$mobile,PDO::PARAM_STR);
        $rows=$command->queryRow(); 

        if($rows == null){
            return array();
        }
    	return $rows;
    	
    }

    public static function getWinUserCountById($lotteryactivityId, $lottery){
        if(!is_array($lottery)) return array();
        
        $connection = Yii::app()->db;
        
        $sql = 'select count(1) from lotterysms inner join lottery on lotterysms.lotteryId=lottery.id where lottery.lotteryactivityId=:lotteryactivityId and lotterysms.lotteryId in (:lotteryarray) and lotterysms.toPhone is not null';
        //return $sql;
        $lotteryarray = implode(',', $lottery);
        $command = $connection->createCommand($sql);
        $command->bindParam(":lotteryactivityId",$lotteryactivityId,PDO::PARAM_STR);
        $command->bindParam(":lotteryarray",$lotteryarray,PDO::PARAM_STR);
        $rows=$command->queryScalar();
        if($rows){
            return $rows;
        }
        return 0;
    } 
}