<?php
class CityHelper {
	
	public static function getProvinces() {
		
		$sql = "select * from mzone_area where reid = 0 ";
		$command = Yii::app()->pwind->createCommand ( $sql );
		
		$rs = array ();
		foreach ( $command->queryAll () as $row ) {
			$rs [] = $row;
		}
		
		return $rs;
	}
	
	public static function getCityByProvince( $province_id ) {
		
		$sql = "select * from mzone_area where reid = :provid";
		$command = Yii::app()->pwind->createCommand ( $sql );
		$command->bindParam( ':provid', $province_id, PDO::PARAM_INT );
		
		$rs = array ();
		foreach ( $command->queryAll (  ) as $row ) {
			$rs [] = $row;
		}
		
		return $rs;
	}
	
	public static function getCityName($city_id) {
		$cmscity = Yii::app()->params['cmscity'];
		if(!isset($cmscity[$city_id])){
			return $cmscity['2500'];
		}
		return $cmscity[$city_id]; 
	}


}





