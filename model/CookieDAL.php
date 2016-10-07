<?php

namespace model;

class CookieDAL {
	private static $FILE_NAME = 'db/cookies.json';


	public function collectCookies() : array {
		$f_open = fopen(self::$FILE_NAME, 'r');
		$f_read = fread($f_open, filesize(self::$FILE_NAME));
		return json_decode($f_read, true);
	}


	public function saveCookie(\model\Cookie $c, array $cookies) {
		$f_open = fopen(self::$FILE_NAME, 'w');
		$cookies[] = array('cookiename' => $c->cookieName, 'cookiePassword' => $c->cookiePassword, 'time' => $c->cookieTime);
		$encode = json_encode($cookies, true);
		fwrite($f_open, $encode);
		fclose($f_open);
	}

	public function updatePassword(array $cookies) {
		$f_open = fopen(self::$FILE_NAME, 'w');
		$encode = json_encode($cookies, true);
		fwrite($f_open, $encode);
		fclose($f_open);
	}
}
