<?php

namespace model;

class User {

	public $username;
	public $password;
	public $passwordRepeat;

	public function __construct(string $username, string $password, string $passwordRepeat = '') {
		$this->username = $username;
		$this->password = $password;
		$this->passwordRepeat = $passwordRepeat;
	}
}
