<?php

namespace model;

class NewUser {
    public $username;
    public $password;
    public $passwordRepeat;

    public function __construct($username, $password, $passwordRepeat) {

    	if (strlen($username) < 3) {
    		throw new \error\ShortUsernameException('Username is to short!');
    	}

    	if (strlen($password) < 6) {
    		throw new \error\ShortPasswordException('Password is short!');
    	}

    	if (strcmp($password, $passwordRepeat) !== 0) {
    		throw new \error\NotMatchingPasswordException('Passwords do not match');
    	}

    	if ($username !== strip_tags($username)) {
    		throw new \error\InvalidCharactersException('Username contains invalid characters');
    	}



        $this->username = strip_tags($username); // Strips HTML tags
                
        $this->password = $password;
        $this->passwordRepeat = $passwordRepeat;
    }
}
