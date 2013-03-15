<?php
class TimeHelper {
	
	static $today = null;
	
	public static function showWelcome() {
		$hour = date ( 'H' );
		if ($hour < 6)
			return '凌晨好';
		elseif ($hour < 12)
			return '上午好';
		elseif ($hour < 14)
			return '中午好';
		elseif ($hour < 18)
			return '下午好';
		else
			return '晚上好';
	}
	
	/**
	 * 
	 * 获取时间差
	 * @param unknown_type $day1
	 * @param unknown_type $day2
	 * @return DateObjectHelper
	 */
	public static function dist( $day1, $day2 ) {
		
		$seconds = strtotime( $day1 ) - strtotime( $day2 );
		
		if ($seconds < 1)
			return $seconds;
		
		$d = $seconds / 86400;
		$d_l = $seconds % 86400;
		$h = $d_l / 3600;
		$h_l = $d_l % 3600;
		$m = $h_l / 60;
		$m_l = $h_l % 60;
		$s = $m_l / 1;
		$s_l = $m_l % 1;
		
		$do = new DateObjectHelper();
		$do->day = floor ( $d );
		$do->hour = floor ( $h );
		$do->min = floor ( $m );
		$do->seconds = floor ( $s );
		return $do;
	}
	
	
	/**
	 * 获取当前日期
	 */
	public static function today(){
		if( !is_null( self::$today ) )
			return self::$today;
		return self::$today = date( 'Y-m-d' ); 	
	}
	
}





