<?php

namespace controller;

require_once('model/User.php');
require_once('model/Users.php');
require_once('model/UserDAL.php');
require_once('model/SessionModel.php');
require_once('model/Cookies.php');
require_once('model/CookieDAL.php');


class LoginController {
	private $loginView;
	private $dateTimeView;
	private $layoutView;
	private $flashMessage;
	private $users;
	private $cookieDAL;
	private $sessionModel;
	private $cookies;

	public function __construct(\view\LoginView $loginView,
								\view\DateTimeView $dateTimeView,
								\view\LayoutView $layoutView,
								\model\FlashMessageModel $flashMessage,
								\model\SessionModel $sessionModel) {
		$this->loginView = $loginView;
		$this->dateTimeView = $dateTimeView;
		$this->layoutView = $layoutView;
		$this->flashMessage = $flashMessage;
		$this->sessionModel = $sessionModel;
		$this->cookieDAL = new \model\CookieDAL();
		$this->cookies = new \model\Cookies($this->cookieDAL);
	}

	public function login(\model\Users $u) {
		$this->users = $u;
		try {
			$this->tryLoginUser();
		} catch (\error\UsernameMissingException $e) {
			$this->loginView->setUsernameIsMissingMessage();

		} catch(\error\PasswordMissingException $e) {
			$this->loginView->setPasswordMissingMessage();
			$this->loginView->setUsername();

		} catch (\error\NoSuchUserException $e) {
			$this->loginView->setWrongCredentialsMessage();
			$this->loginView->setUsername();
		}
		

		$this->renderLogin();
	}

	public function logout() {

		if ($this->sessionModel->isLoggedIn()) {
			$this->sessionModel->logout();
			$this->flashMessage->setLoginFlash($this->loginView->byeMessage());
		}

		if ($this->loginView->isCookieSet()) {
			$this->loginView->removeCookies();
			$this->cookies->removeCookies($this->loginView->getStoredCookieInfo());
		}

		$this->redirectToSelf();
	}

	public function tryLoginWithCookies() {
		$cookie = $this->loginView->getStoredCookieInfo();

		if ($this->cookies->isStored($cookie->cookiePassword) && $this->sessionModel->isLoggedIn() === false) {
			$this->flashMessage->setLoginFlash($this->loginView->backWithCookieMessage());
			$this->sessionModel->login();
			$this->redirectToSelf();
		} else {
			$this->renderLogin();
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
		$this->flashMessage->setLoginFlash($this->loginView->welcomeAndRememberMessage());
	}

	private function setNormalLoginFlash() {
		$this->flashMessage->setLoginFlash($this->loginView->welcomeMessage());
	}

	private function renderLogin() {
		$this->layoutView->render($this->sessionModel->isLoggedIn(), 
								  $this->loginView, 
								  $this->dateTimeView);
	}

	private function tryLoginUser() {
		$userCredentials = $this->loginView->getUserinformation();
		$this->users->searchForUser($userCredentials);

		// Prevents login when already logged in
		if (!$this->sessionModel->isLoggedIn()) {
			$this->setWelcomeAndLogin();
		}
	}

	private function setWelcomeAndLogin() {
		$this->setSuccessfulFlashMessage();
		$this->sessionModel->login();
		$this->redirectToSelf();
	}

	// TODO: Move to view
	private function redirectToSelf() {
		header('Location: '.$_SERVER['PHP_SELF']);
	}
}