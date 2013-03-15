<?php

/**
 * 模板帮助函数
 * @author mc009
 */
class TmlHelper{
	
	public static function renderJson( $arr ){
		
		if( !is_array( $arr ) )$arr = array( $arr );
		echo CJSON::encode( $arr );exit;
	}
}