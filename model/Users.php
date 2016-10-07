<?php

namespace model;


require_once('SessionModel.php');
require_once('exceptions/NoSuchUserException.php');
require_once('exceptions/AlreadyLoggedInException.php');
require_once('exceptions/UsernameMissingException.php');
require_once('exceptions/PasswordMissingException.php');
require_once('exceptions/ShortPasswordException.php');
require_once('exceptions/ShortUsernameException.php');
require_once('exceptions/NotMatchingPasswordException.php');
require_once('exceptions/BusyUsernameException.php');
require_once('exceptions/InvalidCharactersException.php');


class Users {


	private static $username = 'username';
	private static $password = 'password';


	private $users = array();
	private $userDAL;
	private $userCredentials;
	private $sessionModel;

	public function __construct(UserDAL $db, $u) {
		$this->userDAL = $db;
		$this->userCredentials = $u;
		$this->getUsers();
	}
	
	public function tryToLoginUser(SessionModel $sessionModel) {


		// If the user tries to login when already logged in.
		// Not using message, keeping for logs.
		if ($sessionModel->isLoggedIn()) {
			throw new \error\AlreadyLoggedInException('Already logged in.');
		}

		// Not using message, keeping for logs
		if (empty($this->userCredentials->username)) {
			throw new \error\UsernameMissingException('Username is missing!');
		}

		// Not using message, keeping for logs
		if (empty($this->userCredentials->password)) {
			throw new \error\PasswordMissingException('Password is missing!');
		}

		// Not using message, keeping for logs.
		if ($this->searchForUser() === false) {
			throw new \error\NoSuchUserException('Wrong name or password');
		}
	}

	public function tryToRegisterUser() {

		if (strlen($this->userCredentials->password) < 6) {
			throw new \error\ShortPasswordException('Password has too few characters, at least 6 characters.');
		}

		if (strcmp($this->userCredentials->password, $this->userCredentials->passwordRepeat) !== 0) {
			throw new \error\NotMatchingPasswordException('Passwords do not match.');
		}

		if (strlen($this->userCredentials->username) < 3) {
			throw new \error\ShortUsernameException('Username has too few characters, at least 3 characters.');
		}

		if ($this->searchForUsername() === true) {
			throw new \error\BusyUsernameException('User exists, pick another username.');
		}

		if ($this->userCredentials->username !== strip_tags($this->userCredentials->username)) {
			throw new \error\InvalidCharactersException('Username contains invalid characters.');
		}
	}

	// Ask about this [Using array since saving in json ($user[self::$password / self::$username])]
	private function searchForUser() : bool {
		foreach ($this->users as $user) {
			if ($user[self::$username] === $this->userCredentials->username &&
				$user[self::$password] === $this->userCredentials->password) {
				return true;
			}
		}

		return false;
	}

	private function searchForUsername() : bool {
		foreach ($this->users as $user) {
			if ($user[self::$username] === $this->userCredentials->username) {
				return true;
			}
		}

		return false;
	}

	private function getUsers() {
		$this->users = $this->userDAL->collectUsers();
	}

	public function addNewUser() {
		$this->userDAL->addUser($this->users, $this->userCredentials);
	}
}
