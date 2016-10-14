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
		
		$this->userDAL = new \model\UserDAL();
		$this->users = new \model\Users($this->userDAL);

		try {
			// $this->tryLoginUser();
			$this->newUser = $this->loginView->getUserinformation();
			$this->users->temp_searchForUserWithException($this->newUser);
			$this->sessionModel->temp_alreadyLoggedIn();

		} catch (\error\UsernameMissingException $e) {
			$this->loginView->temp_setUsernameIsMissingMessage();
			return $this->renderLogin();

		} catch(\error\PasswordMissingException $e) {
			$this->loginView->temp_setPasswordMissingMessage();
			$this->loginView->temp_setUsername();
			return $this->renderLogin();

		} catch (\error\NoSuchUserException $e) {
			$this->loginView->temp_setWrongCredentialsMessage();
			$this->loginView->temp_setUsername();
			return $this->renderLogin();

		} 
		catch (\error\AlreadyLoggedInException $e) {
			return $this->renderLogin();

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
		$storedCookiePassword = $this->loginView->getCookiePassword();

		if ($this->cookies->isStored($storedCookiePassword) && $this->sessionModel->isLoggedIn() === false) {
			$this->flashMessage->temp_setLoginFlash($this->loginView->backWithCookieMessage());
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
		$this->flashMessage->temp_setLoginFlash($this->loginView->welcomeAndRememberMessage());
	}

	private function setNormalLoginFlash() {
		$this->flashMessage->temp_setLoginFlash($this->loginView->welcomeMessage());
	}

	private function renderLogin() {
		$this->layoutView->render($this->sessionModel->isLoggedIn(), 
								  $this->loginView, 
								  $this->dateTimeView);
	}

	private function tryLoginUser() {
		$this->newUser = $this->loginView->getUserinformation();
		$this->users->temp_searchForUserWithException($this->newUser);
		if (!$this->sessionModel->isLoggedIn()) {
			$this->setWelcomeAndLogin();
		}
	}

	private function setWelcomeAndLogin() {
		$this->setSuccessfulFlashMessage();
		$this->sessionModel->login();
		$this->redirectToSelf();
		$this->renderLogin();
	}

	// Move to view?
	private function redirectToSelf() {
		header('Location: '.$_SERVER['PHP_SELF']);
	}
}
