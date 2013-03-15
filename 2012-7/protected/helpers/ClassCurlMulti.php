<?php
/*
 * Curl 多线程类
 * 使用方法：
 * ========================
$urls = array("http://baidu.com", "http://dzone.com", "http://google.com");
$mp = new ClassCurlMulti($urls);
$mp->start();
 * ========================
 */
class ClassCurlMulti {
    public $urls = array();
    public $curlopt_header = 1;
    public $method = "GET";
    public $content='';
    public $cateId=0;
    function __construct($urls = false) {
        $this->urls = $urls;
    }
 
    function set_urls($urls) {
        $this->urls = $urls;
        return $this;
     }
 
     function is_return_header($b) {
         $this->curlopt_header = $b;
         return $this;
     }
 
     function set_method($m) {
         $this->medthod = strtoupper($m);
         return $this;
     }
 
     function start() {
         if(!is_array($this->urls) or count($this->urls) == 0){
            return false;
         }
         $curl = $text = array();
         $handle = curl_multi_init();
         foreach($this->urls as $k=>$v){
            $curl[$k] = $this->add_handle($handle, $v);
         }
 
         $this->exec_handle($handle);
         $dump_sql='';
         foreach($this->urls as $k=>$v){
             $text[$k] =  curl_multi_getcontent($curl[$k]);
             preg_match_all("/<span id='doctitle'>(.+?)<\/span>(.+?)<div class=\"content_topp\">(.+?)<\/div>/is",$text[$k],$art_list2);             
             //if(is_array($art_list2[1] && is_array($art_list2[3]))){
             $title=preg_replace("'([\r\n])[\s]+'", "",$art_list2[1][0]);
             $content=preg_replace("'([\r\n])[\s]+'", "", $art_list2[3][0]);;
             //}
             if(empty($title) ||empty($content)){
             	echo $v.' error coming,it`s stop!';exit;
             }
             $dou=$k==0?'':',';
             $dump_sql.="$dou('".$title."','".$content."',".$this->cateId.")";
             curl_multi_remove_handle($handle, $curl[$k]);
         }
         $this->content=$dump_sql;
         curl_multi_close($handle);
     }
     function get_content(){
     	foreach($this->urls as $k=>$v){
     		$content=file_get_contents('http://www.fjmszb.com/index.php?doc-view-335');
     		preg_match_all("/<span id='doctitle'>(.+?)<\/span>(.+?)<div class=\"content_topp\">(.+?)<\/div>/is",$content,$art_list2);
     		var_dump($art_list2);exit;
     		if(is_array($art_list2[1] && is_array($art_list2[3]))){
     			$title=preg_replace("'([\r\n])[\s]+'", "",$art_list2[1][0]);
     			$content=preg_replace("'([\r\n])[\s]+'", "", $art_list2[3][0]);;
     		}
     		if(empty($title) ||empty($content)){
     			echo $v.' error coming,it`s stop!';exit;
     		}
     		$dou=$k==0?'':',';
     		$dump_sql.="$dou('".$title."','".$content."',".$this->cateId.")";
     	}
     	$this->content=$dump_sql;
     }
     //当个网页
     function start_one() {
     	if(!is_array($this->urls) or count($this->urls) == 0){
     		return false;
     	}
     	$curl = $text = array();
     	$handle = curl_multi_init();
     	foreach($this->urls as $k=>$v){
     		$curl[$k] = $this->add_handle($handle, $v);
     	}
     
     	$this->exec_handle($handle);
     
     	foreach($this->urls as $k=>$v){
     
     		$text[$k] =  curl_multi_getcontent($curl[$k]);
     		$this->content=$text[$k];
     		curl_multi_remove_handle($handle, $curl[$k]);
     	}
     	curl_multi_close($handle);
     }
 
     private function add_handle($handle, $url) {
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_URL, $url);
         curl_setopt($curl, CURLOPT_HEADER, $this->curlopt_header);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         curl_multi_add_handle($handle, $curl);
         return $curl;
     }
 
     private function exec_handle($handle) {
         $flag = null;
         do {
            curl_multi_exec($handle, $flag);
         } while ($flag > 0);
     }
}
