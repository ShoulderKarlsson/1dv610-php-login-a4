<?php

namespace view;

require_once('model/User.php');
require_once('model/Cookie.php');


class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private $message = '';
	private $usernameValue = '';

	public function __construct(\model\FlashMessageModel $flashMessage) {

		if ($flashMessage->isLoginFlashSet()) {
			$this->message = $flashMessage->getLoginFlashMessage();

			if ($flashMessage->isLoginUsername()) {
				$this->usernameValue = $flashMessage->getLoginUsernameFlash();
			}
		}


		// if ($flashMessage->isFlashSet()) {
		// 	$this->message = $flashMessage->getFlashMessage();
		//
		// 	if ($flashMessage->isUsernameSet()) {
		// 		$this->usernameValue = $flashMessage->getUsernameValueFlash();
		// 	}
		// }
/*
		if ($flashMessage->isUsernameFlash()) {
			$this->message = $flashMessage->getUsernameFlash();

		} else if ($flashMessage->isPasswordFlash()) {
			$this->usernameValue = $flashMessage->getUsernameValueFlash();
			$this->message = $flashMessage->getPasswordFlash();

		} else if ($flashMessage->isCredentialsFlash()) {
			$this->usernameValue = $flashMessage->getUsernameValueFlash();
			$this->message = $flashMessage->getCredentialsFlash();

		} else if ($flashMessage->isWelcomeFlash()) {
			$this->message = $flashMessage->getWelcomeFlash();

		}  else if ($flashMessage->isByeFlash()) {
			$this->message = $flashMessage->getByeFlash();

		} else if ($flashMessage->isNewUserFlash()) {
			$this->message = $flashMessage->getNewUserFlash();
			$this->usernameValue = $flashMessage->getUsernameValueFlash();

		} else if ($flashMessage->isCookieRemeberFlash()) {
			$this->message = $flashMessage->getCookieRememberFlash();

		} else if ($flashMessage->isWelcomeBackFlash()) {
			$this->message = $flashMessage->getWelcomeBackFlash();
		}
		*/
	}
	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$active = new \model\SessionModel(); // Change this - send in as argument


		if ($active->isLoggedIn()) {
			return $this->generateLogoutButtonHTML($this->message);
		} else {
			return $this->generateLoginFormHTML($this->message);
		}
	}


	public function getUserInformation() {
		return new \model\User($this->getRequestUsername(), $this->getRequestPassword());
	}

	public function getCookieInfo() {
		return new \model\Cookie($this->getRequestUsername());
	}

	// If something goes wrong with tests and flashMessages - it might be these two
	// If so is the case, change it.
	// public function setUsernameFlash(\model\FlashMessageModel $flashMessage) {
	// 	$flashMessage->setUsernameMessage();
	// }

	// public function setPasswordFlash(\model\FlashMessageModel $flashMessage) {
	// 	$flashMessage->setPasswordMessage();
	// 	$flashMessage->setUsernameValueFlash($this->getRequestUsername());
	// }

	public function setUsernameValue() {
		$this->usernameValue = $this->getRequestUsername();
	}

	private function getRequestUsername() : string {
		return $_POST[self::$name];
	}

	private function getRequestPassword() : string {
		return $_POST[self::$password];
	}

	private function getCookiename() : string {
		return $_COOKIE[self::$cookieName];
	}

	public function getCookiePassword() : string {
		return $_COOKIE[self::$cookiePassword];
	}

	public function getStoredCookieInfo() {
		$nc = new \model\Cookie($_COOKIE[self::$cookieName]);
		$nc->cookiePassword = $_COOKIE[self::$cookiePassword];

		return $nc;
	}

	public function wantsToLogin() : bool {
		return isset($_POST[self::$password]) || isset($_POST[self::$name]);
	}

	public function wantsToLogout() : bool {
		return isset($_POST[self::$logout]);
	}

	public function wantsToStoreSession() : bool {
		return isset($_POST[self::$keep]);
	}

	public function isCookieSet() : bool {
		return isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword]);
	}

	public function setClientCookie(\model\Cookie $c) {
		$duration = time() + 3600;
		setcookie(self::$cookieName, $c->cookieName, $duration);
		setcookie(self::$cookiePassword, $c->cookiePassword, $duration);
	}


	public function removeCookies() {
		setcookie(self::$cookieName, '', time() - 10);
		setcookie(self::$cookiePassword, '', time() - 10);
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->usernameValue . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" value="" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />

					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
}
