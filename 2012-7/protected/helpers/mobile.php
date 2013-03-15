<?php
class Mobile{
//////////////////////////
// 手机处理函数
// monkey craps
// 2010 7 9
//////////////////////////

function mzone_mobile_get_area_no( $mobile ){
	
    global $db;

    if( !mzone_mobile_check($mobile) ) {
        return false;
    }
    
    $sql = "SELECT da.id FROM mzone_area da INNER JOIN pw_mobile_numarea pmn ON da.name = CONCAT( pmn.city, '市' )
        WHERE pmn.item_mode='". substr( $mobile, 0, 7 ) ."'";
	
    if( ! $row = $db->get_one( $sql ) ){
        return false;
    }
    return $row['id'];
}

function mzone_mobile_get_area( $mobile ){
	
    global $db;

    if( !mzone_mobile_check($mobile)) {
        return false;
    }
    
    $sql = "SELECT da.id FROM mzone_area da INNER JOIN pw_mobile_numarea pmn ON da.name = CONCAT( pmn.city, '市' )
        WHERE pmn.item_mode='". substr( $mobile, 0, 7 ) ."'";
    return  $row = $db->get_one( $sql );
}



function mzone_mobile_is_guangdong( $mobile ){

	global $cfg_mzone, $db;
	
	if( !mzone_mobile_check($mobile) ) {
        return false;
    }
    
	$sql = "SELECT da.id, da.reid FROM mzone_area da INNER JOIN pw_mobile_numarea pmn ON da.name = CONCAT( pmn.city, '市' )
        WHERE pmn.item_mode=". pwEscape( substr( $mobile, 0, 7 ) );
	
    if( ! $row = $db->get_one( $sql ) ){
        return false;
    }
	if( $cfg_mzone['area_id_guangdong'] != $row['reid'] ){
		return false;
	}
	
	return true;
}

//从boss接口判断是否为广东移动号码
function mzone_check_gd_mobile_from_boss($msisdn,$cmdid='20101'){
	$datatrans = $msisdn.'~6000~1';
	$boseData = mzone_boss_interface($msisdn,$cmdid,$datatrans);
    error_log($msisdn.' ' .$boseData->Datatrans ."\r\n", 3, 'log/check_gd_mobile.log' );
	$boseData->Datatrans = iconv('UTF-8','GBK',$boseData->Datatrans);
    if(!empty($boseData->Datatrans) && substr($boseData->Datatrans, 0, 1) == 0){
    	return true;
    }else{
    	return false;
    }
}


/**
 * 验证是否为手机号码
 */
function mzone_mobile_check( $moibile ){

    return preg_match( '/^[0-9]{11}$/', $moibile );

}

/**
 * 自动找出所有 %%abc%%，然后在 $str_replace 
 * 找出对应值进行替换
 */
function mzone_str_replace($str, $str_replace) {
	
	$str_match = array ();
	
	$keys = array_keys ( $str_replace );
	foreach ( $keys as &$one )
		$one = "%%$one%%";
	$str = str_replace ( $keys, array_values ( $str_replace ), $str );
	
	return $str;
}

/**
 * 从 mobile.config.php 
 * 里读取短信信息，然后进行替换， 
 * 再发送短信 
 * 
 * @author Administrator (2010-7-16)
 * 
 * @param $msg_flag 
 * @param $msg_replace 
 */
function mzone_mobile_sms_send( $msg_flag, $msg_replace, $to, $targetno='10658599' ){

    global  $_mzone_sms_content;

    $content = $_mzone_sms_content[$msg_flag];
    $content = iconv('utf-8','gbk',$content);
    $content = self::mzone_str_replace( $content, $msg_replace );

    $sms = new smssend();

    $sms->SetContent( $content );
    $sms->SetDestTermID( $to ); 
    $sms->SetFeeTermID( $to );
    $sms->SetSrcTermID( $targetno );
    $sms->SetXMLValue();
    $response = $sms->SendSMS();
   
    if( !('OK' == trim($response)) ){
        return false;
    }
    return true;
}

/*
 * 显示号码为 135****9554
 * @param $mobile=
 * */
function mzone_mobile_display( $mobile ){
	return substr( $mobile, 0, 3 ). "****". substr( $mobile, 7 ) ;
}

}
