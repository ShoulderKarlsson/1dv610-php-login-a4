<?php

namespace model;

class Cookie {
	public $cookieName;
	public $cookiePassword;
	public $cookieTime;

	public function __construct($cookieName) {
		$this->cookieName = $cookieName;
		$this->cookieTime = time() + 3600;
	}
}