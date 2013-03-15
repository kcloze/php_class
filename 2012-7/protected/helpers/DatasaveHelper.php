<?php
class DatasaveHelper{
	public static function save($table, $data=array()){
		$model = null;
		$id = $data['id'];
		
		if(!empty($id)){
			$model = SmartdbModel::model($table);
		}else{
			$model = new SmartdbModel($table);
		}

		if($model == null ) return;
		unset($data['id']);
		
		foreach($data as $key=>$item){
			if(is_array($item)){
				$data[$key]=implode(',', $item);
			}
		}
		$model->attributes = $data;
		$model->save();

		return $model;
	}
	
}