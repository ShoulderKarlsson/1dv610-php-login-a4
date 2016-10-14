<?php

namespace model;

class User {

	public $username;
	public $password;

	public function __construct(string $username, string $password) {

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
	}
}
