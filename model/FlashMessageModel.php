<?php

namespace model;

class FlashMessageModel {
	private static $loginUsernameValue = 'FlashMessageModel::LoginUsername';
	private static $registerUsernameValue = 'flashMessage::RegisterUsername';
	private static $loginFlashMessage = 'FlashMessageModel::LoginFlashMessage';
	private static $registerFlashMessage = 'FlashMessageModel::RegisterFlashMessage';

	public function isLoginUsername() : bool {
		return isset($_SESSION[self::$loginUsernameValue]);
	}

	public function isRegisterUsername() : bool {
		return isset($_SESSION[self::$registerUsernameValue]);
	}

	public function isLoginFlashSet() : bool {
		return isset($_SESSION[self::$loginFlashMessage]);
	}

	public function isRegisterFlashSet() : bool {
		return isset($_SESSION[self::$registerFlashMessage]);
	}

	public function getLoginFlashMessage() : string {
		$message = $_SESSION[self::$loginFlashMessage];
		unset($_SESSION[self::$loginFlashMessage]);
		return $message;
	}

	public function getRegisterFlashMessage() : string {
		$message = $_SESSION[self::$registerFlashMessage];
		unset($_SESSION[self::$registerFlashMessage]);
		return $message;
	}

	public function getRegisterUsernameFlash() : string {
		$value = $_SESSION[self::$registerUsernameValue];
		unset($_SESSION[self::$registerUsernameValue]);
		return $value;
	}

	public function getLoginUsernameFlash() : string {
		$value = $_SESSION[self::$loginUsernameValue];
		unset($_SESSION[self::$loginUsernameValue]);
		return $value;
	}

	public function temp_setLoginFlash(string $message) {
		$_SESSION[self::$loginFlashMessage] = $message;
	}

	public function temp_setRegisterFlash(string $message) {
		$_SESSION[self::$registerFlashMessage] = $message;
	}

	public function setRegisterUsernameFlash(string $value) {
		$_SESSION[self::$registerUsernameValue] = $value;
	}

	public function setLoginUsernameFlash(string $value) {
		$_SESSION[self::$loginUsernameValue] = $value;
	}
}
