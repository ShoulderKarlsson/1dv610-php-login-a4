<?php

namespace model;

require_once('exceptions/BusyUsernameException.php');
require_once('exceptions/NoSuchUserException.php');

class Users {
	
	private static $username = 'username';
	private static $password = 'password';
	private $users = array();
	private $userDAL;
	// private $userCredentials;
	// private $sessionModel;

	public function __construct(UserDAL $db) {
		$this->userDAL = $db;
		$this->getUsers();
	}

	public function searchForUsername(\model\NewUser $newUser) {
		foreach($this->users as $storedUser) {
			if ($storedUser[self::$username] === $newUser->username) {
				throw new \error\BusyUsernameException('User exists, pick another username');
			}
		}
	}

	public function searchForUser(\model\User $user) {
		foreach ($this->users as $storedUser) {
			if ($storedUser[self::$username] === $user->username &&
				$storedUser[self::$password] === $user->password) {
				return true;
			}
		}

		throw new \error\NoSuchUserException('Wrong name or password');
	}

	public function addNewUser(\model\NewUser $u) {
		$this->userDAL->addUser($this->users, $u);
	}

	private function getUsers() {
		$this->users = $this->userDAL->collectUsers();
	}

}