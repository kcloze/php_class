<?php

/**
 * 概率类，主要用于抽奖
 * @author mc
 */
abstract class Probability {
	
	public $data;
	public $param;
	private $base;
	
	public function __construct() {
		$this->param = array ();
	}
	
	public function init() {
		
		$this->base = 1000000;
	}
	
	public function getOne() {
		
		// 不处理数据，只负责返回
		$this->filter ();
		return $this->execute ();
	}
	
	public abstract function filter();
	
	public function execute() {

		// 根据概率选择获取的项目
		$this->param['probability'];
		$to_exec = array();
		$now = 0;
		
		foreach( $this->param['probability'] as $key => $one ){
			
			// 取消不可进行的选择
			if( array_key_exists( $key, $this->param['no'] ) )continue;
			$now = $to_exec[$key] = $this->base * $one + $now;
		}

		$rand = rand( 0, $this->base );
		$result = -1;
		foreach( $to_exec as $key => $one ){
			
			if( $rand < $one ){
				$result = $key;
				break;
			}
		}
		
		$data['result'] = $result;
		return $this->data = $data;
	}
	/**
	 * @return the $data
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * @return the $param
	 */
	public function getParam() {
		return $this->param;
	}
	
	/**
	 * @param field_type $data
	 */
	public function setData($data) {
		$this->data = $data;
	}
	
	/**
	 * @param field_type $param
	 */
	public function setParam($param) {
		$this->param = $param;
	}

}
  











