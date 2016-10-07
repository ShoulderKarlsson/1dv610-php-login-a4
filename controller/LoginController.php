<?php

namespace controller;

require_once('model/User.php');
require_once('model/Users.php');
require_once('model/UserDAL.php');
require_once('model/SessionModel.php');
require_once('model/Cookies.php');
require_once('model/CookieDAL.php');
require_once('model/Validation.php');


class LoginController {
	private $loginView;
	private $dateTimeView;
	private $newUser;
	private $layoutView;
	private $flashMessage;
	private $users;
	private $userDAL;
	private $cookieDAL;
	private $sessionModel;
	private $cookies;

	public function __construct(\view\LoginView $loginView,
								\view\DateTimeView $dateTimeView,
								\view\LayoutView $layoutView,
								\model\FlashMessageModel $flashMessage) {
		$this->loginView = $loginView;
		$this->dateTimeView = $dateTimeView;
		$this->layoutView = $layoutView;
		$this->flashMessage = $flashMessage;
		$this->sessionModel = new \model\SessionModel();
		$this->cookieDAL = new \model\CookieDAL();
		$this->cookies = new \model\Cookies($this->cookieDAL);
	}

	public function login() {

		$this->newUser = $this->loginView->getUserinformation();
		// $this->userDAL = new \model\UserDAL();
		// $this->users = new \model\Users($this->userDAL, $this->newUser);

		$temp_validation = new \model\Validation($this->newUser, $this->sessionModel);

		try {
			// $this->users->tryToLoginUser($this->sessionModel);
			$temp_validation->tryLogin();
		} catch (\error\UsernameMissingException $e) {
			// $this->flashMessage->temp_setLoginFlash($this->loginView->missingUsernameMessage());
			$this->flashMessage->temp_setLoginFlash($e->getMessage());
			return $this->redirectToSelf();

		} catch(\error\PasswordMissingException $e) {
			$this->flashMessage->setLoginUsernameFlash($this->newUser->username);
			// $this->flashMessage->temp_setLoginFlash($this->loginView->missingPasswordMessage());
			$this->flashMessage->temp_setLoginFlash($e->getMessage());
			return $this->redirectToSelf();

		} catch (\error\NoSuchUserException $e) {
			$this->flashMessage->setLoginUsernameFlash($this->newUser->username);
			// $this->flashMessage->temp_setLoginFlash($this->loginView->wrongCredentialsMessage());
			$this->flashMessage->temp_setLoginFlash($e->getMessage());
			return $this->redirectToSelf();
		} catch (\error\AlreadyLoggedInException $e) {
			return $this->layoutView->render(true, $this->loginView, $this->dateTimeView);
		}

		$this->setSuccessfulFlashMessage();
		$this->sessionModel->login();
		return $this->redirectToSelf();
	}

	public function logout() {

		if ($this->sessionModel->isLoggedIn()) {
			$this->sessionModel->logout();
			$this->flashMessage->temp_setLoginFlash($this->loginView->byeMessage());
		}

		if ($this->loginView->isCookieSet()) {
			$this->loginView->removeCookies();
			$this->cookies->removeCookies($this->loginView->getStoredCookieInfo());
		}

		$this->redirectToSelf();
	}

	public function tryLoginWithCookies() {
		$cookiePW = $this->loginView->getCookiePassword();

		if ($this->cookies->isStored($cookiePW) && $this->sessionModel->isLoggedIn() === false) {
			$this->flashMessage->temp_setLoginFlash($this->loginView->backWithCookieMessage());
			$this->sessionModel->login();
			$this->redirectToSelf();
		} else {
			$this->layoutView->render($this->sessionModel->isLoggedIn(), $this->loginView, $this->dateTimeView);
		}
	}

	private function setCookie() {
		$cookie = $this->loginView->getCookieInfo();
		$this->cookies->saveCookie($cookie);
		$this->loginView->setClientCookie($cookie);
	}

	private function setSuccessfulFlashMessage() {
		$this->loginView->wantsToStoreSession() ?
			$this->setCookieAndCookieFlash() :
			$this->setNormalLoginFlash();
	}

	private function setCookieAndCookieFlash() {
		$this->setCookie();
		$this->flashMessage->temp_setLoginFlash($this->loginView->welcomeAndRememberMessage());
	}

	private function setNormalLoginFlash() {
		$this->flashMessage->temp_setLoginFlash($this->loginView->welcomeMessage());
	}

	private function redirectToSelf() {
		header('Location: '.$_SERVER['PHP_SELF']);
	}
}
