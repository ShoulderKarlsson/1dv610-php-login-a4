<?php

namespace model;


/** 
 * Handles all reading and writing to accounts.json
 */
class UserDAL {

	private static $FILE_NAME = 'db/accounts.json';

	public function collectUsers() : array {
		$f_open = fopen(self::$FILE_NAME, 'r');
		$f_read = fread($f_open, filesize(self::$FILE_NAME));
		return json_decode($f_read, true);
	}

	public function addUser(array $users, $user) {
		$f_open = fopen(self::$FILE_NAME, 'w');
		$users[] = array('username' => $user->username, 'password' => $user->password);
		$encode = json_encode($users, true);
		fwrite($f_open, $encode);
		fclose($f_open);
	}
}
