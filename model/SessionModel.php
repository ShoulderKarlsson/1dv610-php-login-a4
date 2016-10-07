<?php

namespace model;


class SessionModel {
	private static $isLoggedIn = 'SessionModel::isLoggedIn';

	public function isLoggedIn() : bool {
		return isset($_SESSION[self::$isLoggedIn]) && $_SESSION[self::$isLoggedIn] === true;
	}

	public function login() {
		$_SESSION[self::$isLoggedIn] = true;
	}

	public function logout() {
		unset($_SESSION[self::$isLoggedIn]);
	}
}