<?php
class DictHelper{
	
	public static function getGroupDict($group_name){
		$data = PwDataDict::model()->findAllByAttributes(array('group_name'=>$group_name));
		foreach ($data as $row){
			try{
				$rvalue = unserialize($row->value);
			}catch(Exception $e){
				echo 'unserialize error';die();
			}
			$rdata[$row->name] = is_array($rvalue)?$rvalue:$row->value;
		}
		return $rdata;
	}

	public static function getValueDict($group_name,$name){
		$data = PwDataDict::model()->findByAttributes(array('group_name'=>$group_name,'name'=>$name));
		try{
			$rvalue = unserialize($data->value);
		}catch(Exception $e){
			echo 'unserialize error';die();
		}
		$rdata = is_array($rvalue)?$rvalue:$data->value;
		return $rdata;
	}
	
	public static function getNameDict($group_name,$value){
		$rdata = PwDataDict::model()->findAllByAttributes(array('group_name'=>$group_name,'value'=>$value));		
		return $rdata['name'];
	}
	
}