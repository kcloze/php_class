<?php

class CommonHelper {
	
	public static function plainTEXT($text) {
		$text = strip_tags ( $text );
		$text = htmlspecialchars_decode ( $text );
		return $text;
	}
	
	public static function leftTime($time, $format = 'y-m-d', $limit = 604800) {
		$left = mktime () - $time;
		
		if ($left > $limit) {
			return date ( $format, $time );
		}
		$text = '';
		
		//周
		$weeks = floor ( $left / 604800 );
		$left = $left % 604800;
		$text .= empty ( $weeks ) ? '' : weeks . '周';
		if ($weeks > 0)
			return $text . '前';
		
		//天
		$day = floor ( $left / 86400 );
		$left = $left % 86400;
		$text .= empty ( $day ) ? '' : $day . '天';
		if ($day > 0)
			return $text . '前';
		
		//小时
		$hour = floor ( $left / 3600 );
		$left = $left % 3600;
		$text .= empty ( $hour ) ? '' : $hour . '小时';
		if ($hour > 0)
			return $text . '前';
		
		//分钟
		$min = floor ( $left / 60 );
		$left = $left % 60;
		$text .= empty ( $min ) ? '' : $min . '分钟';
		//$text .= (!empty($left) && empty($hour))?$left.'秒':'';
		if ($min > 0)
			return $text . '前';
		
		if ($left > 0)
			return $left . '秒前';
		
		$text .= '刚才';
		
		return $text;
	}
	
	public static function cutString($text, $length, $breaks = '...') {
		$text = trim ( $text );
		if (empty ( $text ))
			return $breaks;
		$breaks = (mb_strlen ( $text, Yii::app ()->charset ) > $length) ? $breaks : '';
		$text = $length ? (mb_substr ( $text, 0, $length, Yii::app ()->charset ) . $breaks) : $text;
		return $text;
	}
	
	/**
	 * 分析文本，提取其中的图片，链接
	 * 
	 * @param mixed $text
	 */
	public static function parseText($text) {
		$p = '/<img[^>]*?src="(?P<images>([^"]*?))"[^>]*?>|<a[^>]*?href="(?P<url>([^"]*?))"[^>]*?>/m';
		$matches = array ();
		preg_match_all ( $p, $text, $matches, PREG_OFFSET_CAPTURE );
		$results = array ();
		foreach ( $matches ['images'] as $img ) {
			if ($img [1] > 0)
				$results ['images'] [] = $img [0];
		}
		foreach ( $matches ['url'] as $url ) {
			if ($url [1] > 0)
				$results ['urls'] [] = $url [0];
		}
		
		return $results;
	}
	
	public static function copyFile($old, $new) {
		if (! file_exists ( $new )) {
			CommonHelper::mkdirs ( $new );
		}
		copy ( $old, $new );
	}
	
	public static function moveFile($old, $new) {
		if (! file_exists ( $new )) {
			CommonHelper::mkdirs ( $new );
		}
		rename ( $old, $new );
	}
	
	/**
	 * 返回text中一个安全的截断位置（不在html标签内的位置）
	 * 
	 * @param mixed $text
	 * @param mixed $pos
	 */
	public static function safePos($text, $pos) {
		$c = mb_substr ( $text, 0, $pos, Yii::app ()->charset );
		$p = mb_strrpos ( $c, '<', 0, Yii::app ()->charset );
		$p1 = mb_strrpos ( $c, '>', 0, Yii::app ()->charset );
		if ($p1 < $p) {
			$pos = $p;
		}
		$p2 = mb_strrpos ( $c, '<a', 0, Yii::app ()->charset );
		if (mb_substr ( $c, $p, 2, Yii::app ()->charset ) == '<a') {
			$pos = $p2;
		}
		//echo $p2;
		return $pos;
	}
	
	public static function closeTags($html) {
		#put all opened tags into an array
		preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
		$openedtags = $result [1];
		
		#put all closed tags into an array
		preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
		$closedtags = $result [1];
		
		$len_opened = count ( $openedtags );
		# all tags are closed
		if (count ( $closedtags ) == $len_opened) {
			return $html;
		}
		
		$openedtags = array_reverse ( $openedtags );
		# close tags
		for($i = 0; $i < $len_opened; $i ++) {
			if (! in_array ( $openedtags [$i], $closedtags )) {
				if ($openedtags [$i] == 'img' || $openedtags [$i] == 'br')
					continue;
				$html .= '</' . $openedtags [$i] . '>';
			} else {
				unset ( $closedtags [array_search ( $openedtags [$i], $closedtags )] );
			}
		}
		
		return $html;
	}
	
	/**
	 * 把html转化成纯文本，
	 * 
	 * @param string $text
	 * @param bool $ignoreEmpty 重复的空白字符换行都会变成一个空白字符
	 * @return string
	 */
	public static function html2Plain($text, $ignoreEmpty = true) {
		$text = strip_tags ( $text );
		if ($ignoreEmpty) {
			//$text = preg_replace('/&nbsp;/'," ",$text);
			$text = htmlspecialchars_decode ( $text );
			$text = preg_replace ( '/([\s]{2,})|[\r?\n?]|(?:&nbsp;)/', " ", $text );
		}
		return $text;
	}
	
	public static function tagsCloud($tags, $minFontSize, $maxFontSize, $url) {
		$minimumCount = min ( array_values ( $tags ) );
		$maximumCount = max ( array_values ( $tags ) );
		$spread = $maximumCount - $minimumCount;
		$cloudHTML = '';
		$cloudTags = array ();
		
		$spread == 0 && $spread = 1;
		
		foreach ( $tags as $tag => $count ) {
			$size = $minFontSize + ($count - $minimumCount) * ($maxFontSize - $minFontSize) / $spread;
			$cloudTags [] = CHtml::link ( $tag, array_merge ( $url, array ('tag' => $tag, 'type' => 'tag' ) ), array ('style' => 'font-size:' . floor ( $size ) . 'px;line-height:30px;', 'title' => $count ) ) . ' ';
		}
		
		return '<ul>' . join ( " ", $cloudTags ) . '</ul>';
	}
	
	//creates directory tree recursively
	public static function mkdirs($path, $mode = 0777) {
		$dirs = explode ( '/', $path );
		$pos = strrpos ( $path, "." );
		if ($pos === false) { // note: three equal signs 
			// not found, means path ends in a dir not file 
			$subamount = 0;
		} else {
			$subamount = 1;
		}
		
		for($c = 0; $c < count ( $dirs ) - $subamount; $c ++) {
			$thispath = "";
			for($cc = 0; $cc <= $c; $cc ++) {
				$thispath .= $dirs [$cc] . '/';
			}
			if (! file_exists ( $thispath )) {
				//print "$thispath<br>"; 
				mkdir ( $thispath, $mode );
			}
		}
	}
	
	public static function genOrderCode($step = 1) {
		$code = Yii::app ()->params ['orderEx']; //前缀
		$code .= Yii::app ()->user
			->getState ( 'shopCode' ) . '-'; //分店前缀
		$code .= date ( 'ymd' ) . '-'; //时间
		

		//分店订单当天流水记录
		//查询当天当前分店
		$codeGen = CodeGen::model ()->find ( 'date=? and shopId=? and type=?', array (date ( 'Y-m-d' ), Yii::app ()->user
			->getState ( 'shopId' ), 'ORD' ) );
		
		if (! $codeGen) {
			$codeGen = new CodeGen ();
			$codeGen->date = date ( 'Y-m-d' );
			$codeGen->shopId = Yii::app ()->user
				->getState ( 'shopId' );
			$codeGen->type = 'ORD';
			$codeGen->index = 0;
			$codeGen->save ();
		}
		
		$code .= substr ( '000000' . ($codeGen->index + $step), - 5, 5 );
		
		$codeGen->index = $codeGen->index + $step;
		$codeGen->save ();
		
		return $code;
	}
	
	public static function genCustomerCode($step = 1) {
		$code = Yii::app ()->params ['customerEx']; //前缀
		$code .= Yii::app ()->user
			->getState ( 'shopCode' ) . '-'; //分店前缀
		$code .= date ( 'ymd' ) . '-'; //时间
		

		//分店订单当天流水记录
		//查询当天当前分店
		$codeGen = CodeGen::model ()->find ( 'date=? and shopId=? and type=?', array (date ( 'Y-m-d' ), Yii::app ()->user
			->getState ( 'shopId' ), 'CRM' ) );
		
		if (! $codeGen) {
			$codeGen = new CodeGen ();
			$codeGen->date = date ( 'Y-m-d' );
			$codeGen->shopId = Yii::app ()->user
				->getState ( 'shopId' );
			$codeGen->type = 'CRM';
			$codeGen->index = 0;
			$codeGen->save ();
		}
		
		$code .= substr ( '000000' . ($codeGen->index + $step), - 5, 5 );
		
		$codeGen->index = $codeGen->index + $step;
		$codeGen->save ();
		
		return $code;
	}
	
	/**
	 * array(1,50),
	 * array(50,100),
	 * 
	 * @param mixed $r
	 */
	public function accessRole($r = array()) {
		$currentRole = Yii::app ()->user->getState ( 'role' ); //当前用户角色
		if ($currentRole == 925)
			return true;
		foreach ( $r as $i ) {
			$b = $i [0]; //开始
			$e = $i [1]; //结束
			if ($currentRole > $b && $currentRole < $e) {
				//在开始和结束访问内,返回授权
				

				return true;
			}
		}
		return false;
	}
	
	public static function isMobile( $one ){
		return preg_match ( '/^[0-9]{11}$/', $one );
	}

   /**
    *过滤恶意代码
	*
	*/
	public static function uh($str) {
		$farr = array(
			"/\s+/", 
			//过滤多余的空白	    
			"/<(\/?)(script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU",
			  //过滤 <script 等可能引入恶意内容或恶意改变显示布局的代码,
			  //如果不需要插入flash等,还可以加入<object的过滤
			"/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",                                  
			//过滤javascript的on事件
			"/(<|>|\'|\")*/",  
	   );
	   $tarr = array(
			" ",
			"＜\\1\\2\\3＞",           //如果要直接清除不安全的标签，这里可以留空
			"\\1\\2",
			" ",
	   );

	  $str = preg_replace( $farr,$tarr,$str);
	   return $str;
	}
	
	/**
	 * 防止恶意刷新页面，也可以防止cc攻击，3秒内刷新页面5次则停止执行程序
	 */
	public static function bandRefresh() {
		$current_time = time();
		if(isset($_SESSION['visit_time'])){
			$_SESSION['visit_time']++;
		} else {
			$_SESSION['visit_time'] = 1;
			$_SESSION['refresh_time'] = $current_time;
		}
		if($current_time - $_SESSION['refresh_time'] <= 3){
			if($_SESSION['visit_time'] >= 5) die('操作过于频繁，请稍后再试！');
		} else {
			$_SESSION['visit_time'] = 1;
			$_SESSION['refresh_time'] = $current_time;
		}
	}
	
	/**
	 * 防止恶意刷新页面，也可以防止cc攻击，3秒内刷新页面5次则停止执行程序
	 */
	public static function bandRefreshAjax() {
		$current_time = time();
		if(isset($_SESSION['visit_time'])){
			$_SESSION['visit_time']++;
		} else {
			$_SESSION['visit_time'] = 1;
			$_SESSION['refresh_time'] = $current_time;
		}
		if($current_time - $_SESSION['refresh_time'] <= 3){
			if($_SESSION['visit_time'] >= 5) return false;
		} else {
			$_SESSION['visit_time'] = 1;
			$_SESSION['refresh_time'] = $current_time;
		}
		return true;
	}
	
	/**
	 * 防止跨站提交数据
	 */
	public static function isOtherSite() {
		if(isset($_SERVER['HTTP_REFERER'])){
			if(strpos($_SERVER['HTTP_REFERER'], 'http://'.Yii::app()->params['bandAuth']) !== 0 && strpos($_SERVER['HTTP_REFERER'], 'http://www.'.Yii::app()->params['bandAuth']) !== 0) die('错误请求！');
		}
	}
	
	/**
	 * 防止跨站提交数据
	 */
	public static function isOtherSiteAjax() {
		if(isset($_SERVER['HTTP_REFERER'])){
			if(strpos($_SERVER['HTTP_REFERER'], 'http://'.Yii::app()->params['bandAuth']) !== 0 && strpos($_SERVER['HTTP_REFERER'], 'http://www.'.Yii::app()->params['bandAuth']) !== 0) return false;
		}
		return true;
	}
	
	/**
	 * 过滤get,post,cookie
	 */
	public function quotesAll() {
		if($_GET) {
            $_GET = self::quotes($_GET);
            $_GET = self::replaceSpelStr($_GET);
        }
		if($_POST) {
            $_POST = self::quotes($_POST);
            //$_POST = self::replaceSpelStr($_POST);
        }
		if($_COOKIE) $_COOKIE = self::quotes($_COOKIE);
		if($_SERVER) $_SERVER = self::quotes($_SERVER);
		
		if($_GET) $_GET = self::enHtml($_GET);
		if($_POST) $_POST = self::enHtml($_POST);
		if($_COOKIE) $_COOKIE = self::enHtml($_COOKIE);
		if($_SERVER) $_SERVER = self::enHtml($_SERVER);
	}
	
	/**
	 * 过滤输入多维数组或者字符串
	 */
	public function quotes($var) {
		//如果magic_quotes_gpc=Off，那么就开始转义变量
		if (!function_exists('get_magic_quotes_gpc') or !get_magic_quotes_gpc()) {
			if (is_array($var)) {
				foreach ($var as $key => $value) {
					$var[$key] = self::quotes($value);
				}
			} else {
				$var = addslashes($var);
			}
		}
		return $var;
	}
    /**
    * 替换特殊字符
    * 
    * @param mixed $var
    * @return mixed
    */
    public function replaceSpelStr($var){
        //add on 03-07,daxiu
        if (is_array($var)) {
            foreach ($var as $key => $value) {
                $var[$key] = self::replaceSpelStr($value);
            }
        } else {
            $var = str_replace(array('<','>','"',"'",',','%3C','%3E','%22','%27','%3c','%3e','&gt;','&#62;','&quot;','&#34;','&lt;','&#62;','&#39;','%2C','%2c','&#44;'), '', $var);
        }
        return $var;
    }
		
	/**
	 * 多维数组或者字符串转义输出
	 */
	public function unQuotes($var) {
		if (is_array($var)) {
			foreach ($var as $key => $value) {
				$var[$key] = self::unQuotes($value);
			}
		} else {
			$var = stripslashes($var);
		}
		return $var;
	}
	
	/**
	 * 过滤多维数组或者字符串html代码
	 */
	public function enHtml($var) {
		if (is_array($var)) {
			foreach ($var as $key => $value) {
				$var[$key] = self::enHtml($value);
			}
		} else {
			$var = htmlspecialchars($var,ENT_QUOTES);
		}
		return $var;
	}
	
	/**
	 * 多维数组或者字符串还原html代码
	 */
	public function deHtml($var) {
		if (is_array($var)) {
			foreach ($var as $key => $value) {
				$var[$key] = self::deHtml($value);
			}
		} else {
			$var = htmlspecialchars_decode($var,ENT_QUOTES);
		}
		return $var;
	}
	
	/**
	 * trim多维数组或者字符串
	 */
	public function trimArr($var) {
		if (is_array($var)) {
			foreach ($var as $key => $value) {
				$var[$key] = self::trimArr($value);
			}
		} else {
			$var = trim($var);
		}
		return $var;
	}
	
	/**
	 * 判断字符串是否utf8编码
	 */
	public function is_utf8($string) {
	    return preg_match('%^(?:
	          [\x09\x0A\x0D\x20-\x7E]
	        | [\xC2-\xDF][\x80-\xBF]
	        |  \xE0[\xA0-\xBF][\x80-\xBF]
	        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
	        |  \xED[\x80-\x9F][\x80-\xBF]
	        |  \xF0[\x90-\xBF][\x80-\xBF]{2}
	        | [\xF1-\xF3][\x80-\xBF]{3}
	        |  \xF4[\x80-\x8F][\x80-\xBF]{2}
	    )*$%xs', $string);
	}
	
	public function getIp() {
		if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
			//$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			$arr = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
			$ip = trim( $arr[0] );
		}
		else if ($_SERVER["HTTP_CLIENT_IP"])
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		else if ($_SERVER["REMOTE_ADDR"])
			$ip = $_SERVER["REMOTE_ADDR"];
		else if (getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else
			$ip = "Unknown";
		return $ip;
	}
	
	/**
	 * 遍历文件夹
	 * $flag 为true继续遍历下一级文件夹
	 */
	public function readDirFiles($dir,$flag='false') {
		$ret = array();
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if($file != '.' && $file !== '..') {
					 $cur_path = $dir . DIRECTORY_SEPARATOR . $file;
					if($flag && is_dir($cur_path)) {
		   				self::readDirFiles($cur_path);
		    		} else {
		     			$ret[] = $cur_path;
		    		}
				}
	  		}
			closedir($handle);
 		}
 		return $ret;
	}
    public function mzone_get_hide_mobile($mobile,$type='****'){
        return (strlen($mobile) == 11) ? substr_replace($mobile,$type,6,4) : $mobile;
    }
    
    /**
     *$p            当前页码
     *$perpage        显示多少分页数
     *$totalpage    分页总数
     *$querystr        附带的url参数,如 a=1&b=daxiu
     */
    public function mzone_get_midlle_page($p,$perpage=10,$totalpage,$querystr=''){
        $prevpage = $p<=1 ? 1 : ($p-1);
        $nextpage = $p>=$totalpage ? $totalpage : ($p+1);
        $querystr && $querystr = '&'.$querystr;
        $midllenum = ceil($perpage/2);
        $pagestr ='';
        $pagestr .= '<a href="?p='.$prevpage.$querystr.'">上一页</a> '; 

        if($totalpage <= $perpage){
            for($i=1;$i<=$totalpage;$i++){
                if($i==$p)
                    $pagestr .= '<span style="color: red;padding: 0 3px;">'.$i.'</span>';
                else
                    $pagestr .= '<a href="?p='.$i.$querystr.'">'.$i.'</a> '; 
            }
        }elseif(($totalpage - $p) <= $midllenum){
            $pagestr .= '... ';
            for($i=$p-$perpage+($totalpage-$p);$i<=$p;$i++){
                if($i==$p)
                    $pagestr .= '<span style="color: red;padding: 0 3px;">'.$i.'</span>';
                else
                    $pagestr .= '<a href="?p='.$i.$querystr.'">'.$i.'</a> '; 
            }
            for($i=$p+1;$i<=$totalpage;$i++){
                $pagestr .= '<a href="?p='.$i.$querystr.'">'.$i.'</a> '; 
            }
        }elseif($p - $midllenum <= 0){
            for($i=1;$i<=$p;$i++){
                if($i==$p)
                    $pagestr .= '<span style="color: red;padding: 0 3px;">'.$i.'</span>';
                else
                    $pagestr .= '<a href="?p='.$i.$querystr.'">'.$i.'</a> '; 
            }
            for($i=$p+1;$i<=$p+10;$i++){
                $pagestr .= '<a href="?p='.$i.$querystr.'">'.$i.'</a> '; 
            }
            $pagestr .= ' ...';
        }else{
            $pagestr .= '... ';
            for($i=$p-$midllenum;$i<=$p+$midllenum;$i++){
                if($i==$p)
                    $pagestr .= '<span style="color: red;padding: 0 3px;">'.$i.'</span>';
                else
                    $pagestr .= '<a href="?p='.$i.$querystr.'">'.$i.'</a> '; 
            }
            $pagestr .= ' ...';
        }
        $pagestr .= '<a href="?p='.$nextpage.$querystr.'">下一页</a> ';

        return $pagestr;
    }
    /**
     *
     * @param string $string 原文或者密文
     * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
     * @param string $key 密钥
     * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
     * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
     * @example
     *   $a = authcode('abc', 'ENCODE', 'key');
     *   $b = authcode($a, 'DECODE', 'key');  // $b(abc)
     *
     *   $a = authcode('abc', 'ENCODE', 'key', 3600);
     *   $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
     */
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    
    	$ckey_length = 4;
    
    	$key = md5($key ? $key : "kalvin.cn");
    	$keya = md5(substr($key, 0, 16));
    	$keyb = md5(substr($key, 16, 16));
    	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    
    	$cryptkey = $keya.md5($keya.$keyc);
    	$key_length = strlen($cryptkey);
    
    	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    	$string_length = strlen($string);
    
    	$result = '';
    	$box = range(0, 255);
    
    	$rndkey = array();
    	for($i = 0; $i <= 255; $i++) {
    		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
    	}
    
    	for($j = $i = 0; $i < 256; $i++) {
    		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
    		$tmp = $box[$i];
    		$box[$i] = $box[$j];
    		$box[$j] = $tmp;
    	}
    
    	for($a = $j = $i = 0; $i < $string_length; $i++) {
    		$a = ($a + 1) % 256;
    		$j = ($j + $box[$a]) % 256;
    		$tmp = $box[$a];
    		$box[$a] = $box[$j];
    		$box[$j] = $tmp;
    		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    	}
    
    	if($operation == 'DECODE') {
    		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
    			return substr($result, 26);
    		} else {
    			return '';
    		}
    	} else {
    		return $keyc.str_replace('=', '', base64_encode($result));
    	}
    
    }
    

}
