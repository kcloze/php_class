<?php
/*
 * 防止伪造请求
 * 下面是使用方法
 *  <form method="post" action="demo.php">
	<input type="hidden" name="crumb" value="<?php echo Crumb::issueCrumb($uid)?>">
	<input type="text" name="content">
	<input type="submit">
	</form>
	
*
*<?php
		if(Crumb::verifyCrumb($uid, $_POST['crumb'])) {
		    //按照正常流程处理表单
		} else {
		    //crumb校验失败，错误提示流程
		}
 */
class Crumb {

	CONST SALT = "6dBYCQeHUxunU1DmVCyK89H8MsA5your-secret-salt";

	static $ttl = 7200;

	static public function challenge($data) {
		return hash_hmac('md5', $data, self::SALT);
	}

	static public function issueCrumb($uid, $action = -1) {
		$i = ceil(time() / self::$ttl);
		return substr(self::challenge($i . $action . $uid), -12, 10);
	}

	static public function verifyCrumb($uid, $crumb, $action = -1) {
		$i = ceil(time() / self::$ttl);

		if(substr(self::challenge($i . $action . $uid), -12, 10) == $crumb ||
				substr(self::challenge(($i - 1) . $action . $uid), -12, 10) == $crumb)
			return true;

		return false;
	}

}
