<?php
//杀死mysql僵死进程
class KillproCommand extends CConsoleCommand { 

	public function run($args){
		   $sql="SHOW FULL PROCESSLIST";
		   $command=Yii::app()->pwind->createCommand($sql);
		   $result=$command->queryAll();
		   //var_dump($result);exit;
		   foreach($result as $k=>$val){
		   		if($val['Time']>200 ){

		   		    $sql='KILL '.$val['Id'];
		   			$command=Yii::app()->pwind->createCommand($sql);
		   			$result=$command->execute();
		   			echo 'kill mysql id '.$val['Id'].' success '."\n";
		   		
		   		}
		   	}
		
		    echo 'it`s ok ';
	}
	 public function getHelp() {  
        return 'test command help';  
    }  
}