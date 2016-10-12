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

	public function __construct(UserDAL $db) {
		$this->userDAL = $db;
		$this->getUsers();
	}

	public function temp_searchForUsername(string $username) : bool {
		foreach ($this->users as $user) {
			if ($user[self::$username] === $username) {
				return true;
			}
		}

		return false;
	}

	public function temp_searchForUser(\model\User $user) {
		foreach ($this->users as $storedUser) {
			if ($storedUser[self::$username] === $user->username &&
				$storedUser[self::$password] === $user->password) {
				return true;
			}
		}

		return false;
	}

	public function temp_searchForBusyUsernameWithException(\model\NewUser $newUser) {
		foreach($this->users as $storedUser) {
			if ($storedUser[self::$username] === $newUser->username) {
				throw new \error\BusyUsernameException('Busy username!');
			}
		}
	}

	public function temp_searchForUserWithException(\model\User $user) {
		foreach ($this->users as $storedUser) {
			if ($storedUser[self::$username] === $user->username &&
				$storedUser[self::$password] === $user->password) {
				return true;
			}
		}

		// Not using message, keeping for logs.
		throw new \error\NoSuchUserException('Wrong name or password');
	}

	public function searchForUser() : bool {
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

	public function temp_addNewUser(\model\NewUser $u) {
		$this->userDAL->addUser($this->users, $u);
	}
}
