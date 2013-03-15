<?php
/**
 * wap 通用helper
 * @author mc009
 */
class WapHelper {
	
	/**
	 * 获取当前访问者白名单
	 */
	public static function getBaimingdan() {
		
		if (isset ( $_SERVER ['HTTP_X_UP_CALLING_LINE_ID'] )) {
			$phone_bmd = $_SERVER ['HTTP_X_UP_CALLING_LINE_ID'];
		} elseif (isset ( $_SERVER ['HTTP_X_UP_SUBNO'] )) {
			$phone_bmd = $_SERVER ['HTTP_X_UP_SUBNO'];
			$phone_bmd = preg_replace ( '/(.*)(11[d]{ 9 })(.*)/i', '2', $getphone );
		} elseif (isset ( $_SERVER ['DEVICEID'] )) {
			$phone_bmd = $_SERVER ['DEVICEID'];
		} else {
			$phone_bmd = $_SERVER ['DEVICEID'];
		}
		if (strlen ( $phone_bmd ) > 11) {
			$phone_bmd = substr ( $phone_bmd, - 11 );
		}
		return $phone_bmd;
	}

}
