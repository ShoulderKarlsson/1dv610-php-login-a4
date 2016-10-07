<?php
namespace model;

class Validation {
    private $authenticationHandler;
    private $user;
    private $users;
    private $userDAL;


    public function __construct(\model\User $user, \model\SessionModel $sm) {
        $this->user = $user;
        $this->authenticationHandler = $sm;
        $this->getDALAndUsers();
    }

    private function getDALAndUsers() {
        $this->userDAL = new \model\userDAL();
        $this->users = new \model\Users($this->userDAL);
    }

    public function tryLogin() {

		// If the user tries to login when already logged in.
		// Not using message, keeping for logs.
		if ($this->authenticationHandler->isLoggedIn()) {
			throw new \error\AlreadyLoggedInException('Already logged in.[Validation]');
		}

		// Not using message, keeping for logs
		if (empty($this->user->username)) {
			throw new \error\UsernameMissingException('Username is missing! [Validation]');
		}

		// Not using message, keeping for logs
		if (empty($this->user->password)) {
			throw new \error\PasswordMissingException('Password is missing![Validation]');
		}

		// Not using message, keeping for logs.
		if ($this->users->temp_searchForUser($this->user) === false) {
			throw new \error\NoSuchUserException('Wrong name or password[Validation]');
		}
    }

    public function tryRegister() {
		if (strlen($this->user->username) < 3) {
			throw new \error\ShortUsernameException('Username has too few characters, at least 3 characters. [Validation]');
		}

		if (strlen($this->user->password) < 6) {
			throw new \error\ShortPasswordException('Password has to few characters, atleast 6 characters.[Validation]');
		}

		if (strcmp($this->user->password, $this->user->passwordRepeat) !== 0) {
			throw new \error\NotMatchingPasswordException('Passwords do not match.[Validation]');
		}

		if ($this->users->temp_searchForUsername($this->user->username) === true) {
			throw new \error\BusyUsernameException('User exists, pick another username.[Validation]');
		}

		if ($this->user->username !== strip_tags($this->user->username)) {
			throw new \error\InvalidCharactersException('Username contains invalid characters.[Validation]');
		}
    }
}
