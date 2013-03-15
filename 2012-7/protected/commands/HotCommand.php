<?php
class HotCommand extends CConsoleCommand { 

	public function run($args){
		
		   $command=Yii::app()->db->createCommand();
		   $result=$command->select('Id,Url,ActiveEndDate,ActiveStartDate')->from('news_articles')->where('CateId=85')->queryAll();
		   
		   foreach($result as $k=>$val){
		   	//if(strtotime($val['ActiveEndDate']) >time() && time() > strtotime($val['ActiveStartDate'])){
		   		if(preg_match('/^http:\/\/(\w+)\./', $val['Url'],$matches)){
		   			if($matches[1] && $matches[1]!='www'){
		                 $actType=$matches[1];
		   				$command->reset();
		   				//echo $command->select('count(*) as num')->from('act_join_list')->where('acttype=:type',array(':type'=>$actType))->text."\n";
		   				$count=$command->select('count(*) as num')->from('act_join_list')->where('acttype=:type',array(':type'=>$actType))->queryScalar();
		   				$command->reset();
		   				$command->update('news_articles',array('Views'=>$count,'ActId'=>$actType),'Id=:id',array(':id'=>$val['Id']));
		   			    echo $val['Id'].' / '.$count.' / '.$actType."\n";
		   			}
		   		}
		   		if(preg_match('/\/act\/(\w+)/', $val['Url'],$matches)){
		   			if($matches[1] && $matches[1]!='www'){
		                 $actType=$matches[1];
		   				$command->reset();
		   				//echo $command->select('count(*) as num')->from('act_join_list')->where('acttype=:type',array(':type'=>$actType))->text."\n";
		   				$count=$command->select('count(*) as num')->from('act_join_list')->where('acttype=:type',array(':type'=>$actType))->queryScalar();
		   				$command->reset();
		   				$command->update('news_articles',array('Views'=>$count,'ActId'=>$actType),'Id=:id',array(':id'=>$val['Id']));
		   			    echo $val['Id'].' / '.$count.' / '.$actType."\n";
		   			}
		   		}
		   		
		   	}
		   //} 
		    echo 'it`s ok ';
		   //var_dump($result);
	}
	 public function getHelp() {  
        return 'test command help';  
    }  
}