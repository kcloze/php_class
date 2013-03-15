<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = Admin::model()->find('username=:username', array(':username'=>$this->username));
		
		if($user == null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($user->password != md5($this->password.Yii::app()->params['siteHash']))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else{
			$this->errorCode=self::ERROR_NONE;
			
            $this->setState('id', $user->id);
            $this->setState('name', $user->username);
			
		}
		return !$this->errorCode;
	}
}