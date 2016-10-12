<?php

namespace view;

require_once('model/NewUser.php');

class RegisterView {
    private static $name = 'RegisterView::UserName';
	private static $password = 'RegisterView::Password';
	private static $passwordRepeat = 'RegisterView::PasswordRepeat';
	private static $messageId = 'RegisterView::Message';
    private static $register_get = 'register';
    private $message = '';
    private $username = '';

    public function __construct(\model\FlashMessageModel $flashMessage) {
        if ($flashMessage->isRegisterFlashSet()) {
			$this->message = $flashMessage->getRegisterFlashMessage();

            if ($flashMessage->isRegisterUsername()) {
                $this->username = $flashMessage->getRegisterUsernameFlash();
            }
		}
    }


    public function getNewUsercredentials() {
        return new \model\NewUser($this->getRequestUsername(),
                                  $this->getRequestPassword(),
                                  $this->getPasswordRepeat());
    }

    public function wantsToAccsessRegister() : bool {
        return isset($_GET[self::$register_get]);
    }

    public function wantsToRegister() : bool {
        return isset($_POST[self::$name]) && isset($_POST[self::$password]) && isset($_POST[self::$passwordRepeat]);
    }

        public function shortPasswordMessage() : string {
        return 'Password has too few characters, at least 6 characters.';
    }

    public function notMatchingPasswordMessage() : string {
        return 'Passwords do not match.';
    }

    public function shortUsernameMessage() : string {
        return 'Username has too few characters, at least 3 characters.';
    }

    public function newUserMessage() : string {
        return "Registered new user.";
    }

    public function invalidCharactersMessage() : string {
        return 'Username contains invalid characters.';
    }

    public function busyUsernameMessage() : string {
        return 'User exists, pick another username.';
    }


    // #########################################################
    // Functions below are used when testing without redirect!!
    
    public function temp_shortUsernameMessage() {
        $this->message = $this->shortUsernameMessage();
    }

    public function temp_setShortPassword() {
        $this->message = $this->shortPasswordMessage();
    }

    public function temp_setNotMatchingPassword() {
        $this->message = $this->notMatchingPasswordMessage();
    }

    public function temp_invalidCharactersMessage() {
        $this->message = $this->invalidCharactersMessage();
    }

    public function temp_busyUsernameMessage() {
        $this->message = $this->busyUsernameMessage();
    }

    public function temp_setUsername() {
        $this->username = strip_tags($this->getRequestUsername());
    }







    public function response() {
        return '
            <form method="post" action="?register" enctype="multipart/form-data">
                <fieldset>
                    <legend>Register a new user - Write username and password</legend>
                    <p id="' . self::$messageId . '">' . $this->message . '</p>
                    <label for="' . self::$name .'">Username : </label>
                    <input type="text" value="' . $this->username . '" size="20" name="' . self::$name . '" id="' . self::$name . '"/>
                    <br>
                    <label for="' . self::$password . '">Password :</label>
                    <input type="password" size="20" name="' . self::$password . '" id="' . self::$password . '" />
                    <br>
                    <label for ="' . self::$passwordRepeat . '">Repeat password :</label>
                    <input type="password" name="' . self::$passwordRepeat . '" id="' . self::$passwordRepeat . '" />
                    <br>
                    <input type="submit" size="20" name="DoRegistration" id="submit" value="Register" />
                </fieldset>
            </form>
        ';
    }

    private function getRequestUsername() : string {
        return $_POST[self::$name];
    }

    private function getRequestPassword() : string {
        return $_POST[self::$password];
    }

    private function getPasswordRepeat() : string {
        return $_POST[self::$passwordRepeat];
    }
}
