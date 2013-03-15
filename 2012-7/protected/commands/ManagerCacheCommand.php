<?php
class ManagerCacheCommand extends CConsoleCommand { 

	public function run($args){
		echo date('Y-m-d',strtotime('2011-11-08 23:59:00'));exit;
		Yii::app()->cache->flush();
	}
	
	 public function getHelp() {  
        return 'test command help';  
    }  
}
