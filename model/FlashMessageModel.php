<?php

namespace model;

class FlashMessageModel {
	private static $loginUsernameValue = 'FlashMessageModel::LoginUsername';
	private static $loginFlashMessage = 'FlashMessageModel::LoginFlashMessage';

	public function isLoginUsername() : bool {
		return isset($_SESSION[self::$loginUsernameValue]);
	}

	public function isLoginFlashSet() : bool {
		return isset($_SESSION[self::$loginFlashMessage]);
	}

	public function getLoginFlashMessage() : string {
		$message = $_SESSION[self::$loginFlashMessage];
		unset($_SESSION[self::$loginFlashMessage]);
		return $message;
	}

	public function getLoginUsernameFlash() : string {
		$value = $_SESSION[self::$loginUsernameValue];
		unset($_SESSION[self::$loginUsernameValue]);
		return $value;
	}

	public function setLoginFlash(string $message) {
		$_SESSION[self::$loginFlashMessage] = $message;
	}

	public function setLoginUsernameFlash(string $value) {
		$_SESSION[self::$loginUsernameValue] = $value;
	}
}
