<?php

namespace model;


/**
 * Later, remove repeat if not necessary
 */

class User {

	public $username;
	public $password;
	public $passwordRepeat;

	public function __construct(string $username, string $password, string $passwordRepeat = '') {

		// Not using message, keeping for logs
		if (empty($username)) {
			throw new \error\UsernameMissingException('Username is missing');
		}

		// Not using message, keeping for logs
		if (empty($password)) {
			throw new \error\PasswordMissingException('Password is missing');
		}


		

		$this->username = $username;
		$this->password = $password;
		$this->passwordRepeat = $passwordRepeat;
	}
}
