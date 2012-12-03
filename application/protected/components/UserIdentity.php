<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
  private $_id = null;
	private $_roles = array();
	private $_userData = null;
	private $_auth = null;
	//private $_privileges = array();
	//private $_access = array();
	
	public function authenticate()
	{
		$this->_userData = Users::model()->findByAttributes(array('email' => $this->username));
		if (empty($this->_userData)) {
		$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		else if($this->_userData->verifyPassword($this->password)) {
			$this->errorCode=self::ERROR_NONE;
			$this->setupIdentity();
		}
		else {
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}

		return !$this->errorCode;
	}
	
	private function setupIdentity()
	{
		$this->_id = $this->_userData->id;
		$this->setState('email', $this->_userData->email);
		$this->setState('id', $this->_userData->id);
		$this->setState('firstName', $this->_userData->firstName);
		$this->setState('lastName', $this->_userData->lastName);		
	}
}
