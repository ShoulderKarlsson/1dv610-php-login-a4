<?php

namespace model;

class NewUser {
    public $username;
    public $password;
    public $passwordRepeat;

    public function __construct($username, $password, $passwordRepeat) {


        $this->username = $username;
        $this->password = $password;
        $this->passwordRepeat = $passwordRepeat;
    }
}
