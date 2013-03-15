<?php

class MzoneCaller{

    private $url;
    private $timeout = 3;

    private $post;

    public function __construct( $uid=0 ){
    	
    	empty( $uid ) && $uid = UserHelper::getUid();

        $p = UserHelper::getProfile();
        $this->post = array( 'server_call' => '1', 'winduid'=> $uid, 'pwd' => $p['password'] );
    }

    public function add_post( $arr ){

        if( !is_array( $arr ) )return false;
        foreach( $arr as $key=>$val )$this->post[$key] = $val;
        return $this;
    }

    public function set_url( $url ){

        $this->url = $url;
        return $this;
    }

    public function set_timeout( $timeout ){

        $this->timeout = $timeout;
        return $this;
    }

    private function get_post_data(){

        $post_str = http_build_query( $this->post );
        
        return $post_str;
    }

    public function clear_post(){

        $this->post = array( 'server_call' => '1', 'userid'=> $userid );
    }

    public function do_request(){

        $url = $this->url;
        if( !strpos( $url, 'http://' ) ) {
	        $url = Yii::app()->params['server_caller_ip']. $url;
//          $url = "http://mg.com/". $url;
        }
        $post_str = $this->get_post_data();

        $curl = curl_init(); 

        curl_setopt($curl, CURLOPT_URL, $url );
        curl_setopt($curl, CURLOPT_POST, 1);
        //传递数据
        //把返回来的cookie信息保存在$cookie_file文件中
        //设定返回的数据是否自动显示
        //设定是否显示头信息
        //设定是否输出页面内容

        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_str);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout );
        $return = curl_exec($curl);
        if( curl_errno( $curl ) ) {
            $return = json_encode(array(
                'fail',
                'error server connect',
                ));
        }
        curl_close($curl); //get data after login

        // 处理结果并返回
        return $return;
    }


}
