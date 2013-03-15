<?php
class DataHelper{
	public static function save($table, $data=array()){
		$model = null;
		$id = $data['id'];
		
		if(!empty($id)){
			$model = SmartModel::model($table);
		}else{
			$model = new SmartModel($table);
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
	
	public static function resource($id){
		$ars = ActivityResource::model()->findByPk($id);
		return $ars->render();
	}
}