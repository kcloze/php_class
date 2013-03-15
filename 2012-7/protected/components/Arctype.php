<?php
/**
 * 管理栏目类型 * @author mc009 */

class Arctype {
	
    public static function getTable( $arctype_id ){
        
    	$sql = "select dc.`maintable`, dc.`addtable` from mzone_channeltype dc inner join
            mzone_arctype da on da.channeltype = dc.id where da.id=:arctype_id";

    	$command = Yii::app()->pwind->createCommand ( $sql );
		$command->bindParam( ':arctype_id', $arctype_id, PDO::PARAM_INT );
		
        if( ! $row = $command->queryRow() ){
            return false;
        }

        return $row;
    }
}







