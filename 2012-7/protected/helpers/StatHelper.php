<?php
/**
 * 统计socket接口
 */
class StatHelper{
	
	public static function mzone_stat_socket($data, $_user_boss_data = null) {
/*
		if(Yii::app()->user->windboss){
			$data['bname'] = Yii::app()->user->windboss->type;
			$data['bname'] = Yii::app()->user->windboss->name;
		}
*/
		$host = '192.168.20.90';
		$port = 8002;
		$file = '/index.php/actionLog:log';
		if (is_array($data)) {
			foreach ( $data as $k => $v ) {
				
				$file .= ($k && $v) ? "/" . $k . "/" . $v : "";
			}
		} else {
			return false;
		}

		$fp = @fsockopen ( "$host", $port, &$errno, &$errstr, 3 );
		
		if (! $fp)
			return false;
			//echo "$errstr ($errno)<br />\n";
		else {
			$out = "GET $file HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n\r\n";
			
			fwrite ( $fp, $out );
			$rs = "";
			while ( ! feof ( $fp ) ) {
				$rs .= fgets ( $fp, 128 );
			}
			fclose ( $fp );
		}
		return $rs;
	
	}
}