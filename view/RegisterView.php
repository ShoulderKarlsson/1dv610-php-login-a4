<?php

namespace view;

require_once('model/NewUser.php');

class RegisterView {
    private static $name = 'RegisterView::UserName';
	private static $password = 'RegisterView::Password';
	private static $passwordRepeat = 'RegisterView::PasswordRepeat';
	private static $messageId = 'RegisterView::Message';
    private $message = '';
    private $username = '';
    private static $register_get = 'register';

    public function __construct(\model\FlashMessageModel $flashMessage) {
        if ($flashMessage->isRegisterFlashSet()) {
			$this->message = $flashMessage->getRegisterFlashMessage();

            if ($flashMessage->isRegisterUsername()) {
                $this->username = $flashMessage->getRegisterUsernameFlash();
            }

			// if ($flashMessage->isUsernameSet()) {
			// 	$this->username = $flashMessage->getUsernameValueFlash();
			// }
		}

    /*
        if ($flashMessage->isShortPasswordFlash()) {
            $this->message = $flashMessage->getShortPasswordFlash();
            $this->username = $flashMessage->getUsernameValueFlash();

        } else if ($flashMessage->isNotMatchingPasswordFlash()) {
            $this->message = $flashMessage->getNotMatchingPasswordFlash();
            $this->username = $flashMessage->getUsernameValueFlash();

        } else if ($flashMessage->isShortUsernameFlash()) {
            $this->message = $flashMessage->getShortUsernameFlash();
            $this->username = $flashMessage->getUsernameValueFlash();

        } else if ($flashMessage->isBusyUsernameFlash()) {
            $this->message = $flashMessage->getBusyUsernameFlash();
            $this->username = $flashMessage->getUsernameValueFlash();
        } else if ($flashMessage->isInvalidFlash()) {
            $this->message = $flashMessage->getInvalidFlash();
            $this->username = $flashMessage->getUsernameValueFlash();
        }
        */
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


    public function getNewUsercredentials() : \model\NewUser {
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
}
