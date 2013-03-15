<?php

class MzoneGetBossHelper{

	//用户登陆的时候设置boss数据入库并且写入session
	public function mzone_set_bossdata($men){
		//ini_set('display_errors', 1);
		//error_reporting(E_ALL ^ E_NOTICE);
		$value_index = array('brand_value','brand_chinese','start_time','count_day','area','area_code','taocan');
		$sql = "SELECT * FROM pw_mzone_bossdata WHERE mobile='".$men['authmobile']."'";
		$command = Yii::app()->mzone->createCommand($sql);
        $rs = $command->queryRow();
		//线上环境，并且当天大于等于下个月结日，则需读boss
		if((!$rs or !$rs['brand_value']
			 or !$rs['brand_chinese'] or !$rs['start_time']
			 or !$rs['count_day'] or !$rs['area']
			 or !$rs['area_code'] or !$rs['taocan']
			 or date('Y-m-d') >= $rs['count_day']) and $_SERVER['HTTP_HOST'] == 'www.m-zone.cn'){
			$rs = MzoneGetBossHelper::mzone_get_bossdata($men['authmobile'],$value_index);
			MzoneGetBossHelper::mzone_save_bossdata($men['authmobile'],$men['uid'],$rs);
		}
		$_SESSION['mzone_user_bossdata'] = $rs;
	}

	//boss数据入库
	public function mzone_save_bossdata($mobile,$uid,$data){
		$pwSQL = MzoneGetBossHelper::sqlSingle(array(
			'uid' => $uid,
			'mobile' => $mobile,
			'brand_chinese'	=> $data['brand_chinese'],
			'brand_value' => $data['brand_value'],
			'area' => $data['area'],
			'area_code' => $data['area_code'],
			'start_time'=> $data['start_time'],
			'count_day' => $data['count_day'],
			'taocan' => $data['taocan'],
			'updatetime' => date('Y-m-d H:i:s'),
		));
        $sql = "REPLACE INTO pw_mzone_bossdata SET ".$pwSQL;
		//$db->update("REPLACE INTO pw_mzone_bossdata SET ".$pwSQL);
        $command = Yii::app()->mzone->createCommand($sql);
        $command->query();
	}

	/*
	 * 实时读boss数据，$value_index可以是字符串，也可以是数组
	 * 示例 mzone_get_bossdata('13567894321',array('balance','m_value'));
	 *
	 * brand_value -> 获得品牌(数字)
	 * brand_chinese -> 获得品牌(文字)
	 * start_time -> 获得激活时间
	 * balance -> 获得余额
	 * sms_left -> 短信剩余条数
	 * isopen_gprs -> 获得是否开通GPRS套餐
	 * count_day -> 获得下次月结日
	 * m_value -> 获得M值余额
	 * gprs -> 获得GPRS套餐
	 * gprs_left -> 获得GPRS套餐剩余流量
	 * this_voice_set -> 获得本月语音套餐
	 * next_voice_set -> 获得下月语音套餐
	 * this_gprs_set -> 获得本月GPRS套餐
	 * next_gprs_set -> 获得下月GPRS套餐
	 * area -> 获得归属地
	 * area_code -> 获得归属地代码
	 * taocan -> 获得用户业务
	 */
	public function mzone_get_bossdata($mobile,$value_index){
		if(!is_array($value_index)) $value_index = array($value_index);
		$arr = array();
		require_once('/var/www/mzone_v2/mzone/mzone/interface/boss_data.class.php');
		$boss = new boss($mobile);
		foreach ($value_index as $row){
			$arr[$row] = $boss->getBossValue($row);
		}
		return $arr;
	}

    public function sqlSingle($array, $strip = true) {
        if (!MzoneGetBossHelper::isArray($array)) return ''; // modified@2010-7-2
        $array = MzoneGetBossHelper::sqlEscape($array, $strip, true);
        $str = '';
        foreach ($array as $key => $val) {
            $str .= ($str ? ', ' : ' ') . MzoneGetBossHelper::sqlMetadata($key) . '=' . $val;
        }
        return $str;
    }
    /**
     * 通用多类型混合转义函数
     * @param $var
     * @param $strip
     * @param $isArray
     * @return mixture
     */
    public function sqlEscape($var, $strip = true, $isArray = false) {
        if (is_array($var)) {
            if (!$isArray) return " '' ";
            foreach ($var as $key => $value) {
                $var[$key] = trim(MzoneGetBossHelper::sqlEscape($value, $strip));
            }
            return $var;
        } elseif (is_numeric($var)) {
            return " '" . $var . "' ";
        } else {
            return " '" . addslashes($strip ? stripslashes($var) : $var) . "' ";
        }
    }
    /**
     * 过滤SQL元数据，数据库对象(如表名字，字段等)
     * @param $data 元数据
     * @param $tlists 白名单
     * @return string 经过转义的元数据字符串
     */
    public function sqlMetadata($data ,$tlists=array()) {
        if (empty($tlists) || !MzoneGetBossHelper::inArray($data , $tlists)) {
            $data = str_replace(array('`', ' '), '',$data);
        }
        return ' `'.$data.'` ';
    }
    /**
     * 是否数组
     * @param $params
     * @return boolean
     */
    public function isArray($params) {
        return (!is_array($params) || !count($params)) ? false : true;
    }
    /**
     * 变量是否在数组中存在
     * @param $param
     * @param $params
     * @return boolean
     */
    public function inArray($param, $params) {
        return (!in_array((string)$param, (array)$params)) ? false : true;
    }

}
