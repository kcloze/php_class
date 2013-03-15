<?php

class TigerMachine{
	
	private $doudou_cost;
	
	/**
	 * @return the $award
	 */
	public function getAward() {
		return $this->award;
	}
	
	/**
	 * @param field_type $award
	 */
	public function setAward($award) {
		$this->award = $award;
	}
	
	/**
	 * 初始化数据
	 * @see www/protected/components/Probability::init()
	 * @param $arr mixed 初始化的参数 
	 */
	public function init( $arr ){

		foreach( $arr as $key=>$one ){
			
			switch( $one[$key] ){
				case 'cost':
					
					
					break;
			}
		}
		
	}
	
	public function filter() {
		
		global $_timeup_award_count, $_timeup_probability_award;
		
		$param = array ();
		$user = Mzone::app ()->user;
		
		// 每个用户只能中一次话费
		$awards = mc_timeup_award_get_list ( 99999, Mzone::app ()->user->winduid );
		foreach ( $awards as $one ) {
			if ($_timeup_probability_award [0] == $one ['award']) {
				$param [no][0] = 1;
			}
		}
		
		// 必须是动感地带用户才能中话费
		if ( ! isset ( $user->windboss [50032][3][1] ) or '11' != $user->windboss [50032][3][1] ) {
			$param [no][0] = 1;
		}
		
		// 不同类型的中奖概率不同
		$param ['probability'] = $this->getProbability();
		
		// 周六日不中话费
		if (in_array ( date ( 'w', Mzone::app ()->timestamp ), array (6, 0 ) )) {
			$param [no][0] = 1;
		}
		
		// 对应奖项如果没了，则清空该项
		if( $row = mc_data_dict_get( 'activity_timeup', 'award_today_'. Mzone::app()->today ) ){
			
			$award_today = $row['value'];
			$award_today_arr = unserialize( $award_today );
			foreach( $award_today_arr as $key => $val ){
				if( $val >= $_timeup_award_count[$key] ){
					$param [no][$key] = 1;
				}
			}
		}
		
		$this->setParam ( $param );
	}
	
	public function after() {
		
		global $_timeup_probability_award, $_timeup_credit_doudou_award, $_timeup_credit_doudou_cost;
		
		$user = Mzone::app ()->user;
		$credit = Mzone::app()->credit;
		$winduid = $user->winduid;
		
		// 记录入库
		if (- 1 !== $this->award ) {
			
			$award = $_timeup_probability_award[$this->award];
			mc_timeup_award_record ( $user->winddb[uid], $user->winddb['mobile'], $user->windboss['20101'][3][2], $award );
		}
		
		// 处理用户中奖效果
		switch ( $this->award ) {
			
			case - 1 :
				// 抽奖失败
				break;
			case 0 :
				// 抽中话费
				break;
			case 1 :
			case 2 :
			case 3 :
			case 7 :
			case 8 :
			case 9 :
				// 抽中豆豆
				$award_dd = $_timeup_probability_award [$this->award];
				$arr = explode( ',', $award_dd );
				
				$_timeup_credit_doudou_award['money'] = $arr[1];
				$credit->addLog ( 'timeup_tm_award', $_timeup_credit_doudou_award, array ('uid' => $winduid, 'username' => stripslashes ( $winddb ['truename'] ), 'ip' => $onlineip ) );
				$credit->sets ( $winduid, $_timeup_credit_doudou_award, true );
				
				break;
			case 4 :
			case 5 :
			case 6 :
				// 抽中道具
				$award_tool = $_timeup_probability_award [$this->award];
				$arr = explode ( ',', $award_tool );
				
				$tools = Tools::getToolByTflag ( $arr [1] );
				$tool = new Tools ( $tools [0] ['id'], false );
				$tool->saveToPeople ( 1 );
				
				break;
		}
		
		// 扣除用户豆豆
		$credit->addLog ( 'timeup_tm', $_timeup_credit_doudou_cost, array ('uid' => $winduid, 'username' => stripslashes ( $winddb ['truename'] ), 'ip' => $onlineip ) );
		$credit->sets ( $winduid, $_timeup_credit_doudou_cost, true );
	
	}
	
	public function getOne() {
		
		$data = parent::getOne ();
		
		$award = $data['result'];
		
		$this->setAward( $award );
		
		$data = $this->getShowNum( $award );
		error_log( implode( ',', $data). "\r\n", 3, 'data' );
		
		return $this->data = $data;
	}
	
	public function getShowNum( $award ){
		
		global $_timeup_probability_name;
		$max = count( $_timeup_probability_name ) - 1;
		
		if( -1 == $award ){
			$arr = array( rand( 1, $max ), rand( 1, $max ) );
			while( !in_array( $num, $arr ) ){
				$num = rand( 1, $max );
			}
			$arr[] = $num;
			shuffle( $arr );
		}else{
			$arr = array( $award, $award, $award );
		}
		error_log( "$award\r\n", 3, 'award' );
		error_log( implode(  ',', $arr ). "\r\n", 3, 'award_arr' );
		
		return $arr;
		
	}
	
	private function getProbability(){
		
		global $_timeup_award_count;
		
		// 以1w 为基数计算
		$timeup_probability = array();
		foreach( $_timeup_award_count as $key=>$one ){
			$timeup_probability[$key] = $one / 1000;
			if( $key == 0 ){
				$timeup_probability[$key] = $one / 30;
			}
		}
		return $timeup_probability;
	}

}









