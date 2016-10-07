<?php

namespace model;

class FlashMessageModel {
	/*
	private static $cookieRememberMessage = 'Welcome and you will be remembered.';
	private static $welcomeBackMessage = 'Welcome back with cookies.';
	private static $newUserMessage = 'Registered new user.';
	private static $usernameMessage = 'Username is missing';
	private static $passwordMessage = 'Password is missing';
	private static $credentialsMessage = 'Wrong name or password';
	private static $welcomeMessage = 'Welcome';
	private static $byeMessage = 'Bye bye!';
	private static $shortPasswordMessage = 'Password has too few characters, at least 6 characters.';
	private static $notMatchingPasswordMessage = 'Passwords do not match.';
	private static $shortUsernameMessage = 'Username has too few characters, at least 3 characters.';
	private static $busyUsernameMessage = 'User exists, pick another username.';
	private static $invalidCharactersMessage = 'Username contains invalid characters.';
	*/

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

	/*
	public function setUsernameMessage() {
		$_SESSION[self::$loginFlashMessage] = self::$usernameMessage;
	}

	public function setShortPasswordMessage() {
		$_SESSION[self::$registerFlashMessage] = self::$shortPasswordMessage;
	}

	public function setBusyUsernameMessage() {
		$_SESSION[self::$registerFlashMessage] = self::$busyUsernameMessage;
	}

	public function setInvalidCharactersMessage() {
		$_SESSION[self::$registerFlashMessage] = self::$invalidCharactersMessage;
	}

	public function setNotMathingPasswordMessage() {
		$_SESSION[self::$registerFlashMessage] = self::$notMatchingPasswordMessage;
	}

	public function setNewRegisterMessage() {
		$_SESSION[self::$loginFlashMessage] = self::$newUserMessage;
	}

	public function setShortUsernameMessage() {
		$_SESSION[self::$registerFlashMessage] = self::$shortUsernameMessage;
	}

	public function setPasswordMessage() {
		$_SESSION[self::$loginFlashMessage] = self::$passwordMessage;
	}

	public function setWrongCredentialsMessage() {
		$_SESSION[self::$loginFlashMessage] = self::$credentialsMessage;
	}

	public function setWelcomeFlash() {
		$_SESSION[self::$loginFlashMessage] = self::$welcomeMessage;
	}

	public function setWelcomeBackFlash() {
		$_SESSION[self::$loginFlashMessage] = self::$welcomeBackMessage;
	}


	public function setCookieWelcomeFlash() {
		$_SESSION[self::$loginFlashMessage] = self::$cookieRememberMessage;
	}

	public function setByeFlash() {
		$_SESSION[self::$loginFlashMessage] = self::$byeMessage;
	}
	*/
}
