<?php

namespace controller;

require_once("view/LoginView.php");
require_once("view/DateTimeView.php");
require_once("view/LayoutView.php");
require_once('view/RegisterView.php');
require_once("controller/LoginController.php");
require_once('controller/RegisterController.php');
require_once('model/FlashMessageModel.php');
require_once('model/SessionModel.php');
require_once('model/Cookies.php');
require_once('model/CookieDAL.php');


class MainController {
	private $loginView;
	private $dateTime;
	private $layoutView;
	private $loginController;
	private $flashMessage;
	private $sessionModel;
	private $registerView;
	private $registerController;

	public function __construct() {
		$this->flashMessage = new \model\FlashMessageModel();
		$this->dateTime = new \view\DateTimeView();
		$this->layoutView = new \view\LayoutView();
		$this->sessionModel = new \model\SessionModel();
		$this->userDAL = new \model\UserDAL();
		$this->users = new \model\Users($this->userDAL);
		$this->loginView = new \view\LoginView($this->flashMessage);
		$this->registerView = new \view\RegisterView($this->flashMessage);
		$this->registerController = new \controller\RegisterController($this->registerView, 
																	   $this->layoutView, 
																	   $this->dateTime, 
																	   $this->flashMessage);

		$this->loginController = new \controller\LoginController($this->loginView, 
																 $this->dateTime, 
																 $this->layoutView, 
																 $this->flashMessage,
																 $this->sessionModel);
	}

	public function init() {

		if ($this->loginView->wantsToLogin()) {
			return $this->loginController->login($this->users);

		} else if ($this->loginView->wantsToLogout()) {
			return $this->loginController->logout();

		} else if ($this->registerView->wantsToRegister()) {
			return $this->registerController->register($this->users);

		} else if ($this->registerView->wantsToAccsessRegister() && 
				   !$this->sessionModel->isLoggedIn()) {

			$this->registerController->presentRegister($this->sessionModel->isLoggedIn());

		} else if ($this->loginView->isCookieSet() && $this->sessionModel->isLoggedIn() === false) {
			$this->loginController->tryLoginWithCookies();

		} else {

			$this->updateCookiePassword();
			return $this->layoutView->render($this->sessionModel->isLoggedIn(), $this->loginView, $this->dateTime);
		}
	}

	/**
	 * Cookiepassword is good for one request.
	 */
	private function updateCookiePassword() {
		$cd = new \model\CookieDAL();
		$c = new \model\Cookies($cd);

		// Updates Cookie password, client and DB
		if ($this->loginView->isCookieSet()) {
			$storedCookie = $this->loginView->getStoredCookieInfo();
			$oldpw = $storedCookie->cookiePassword;
			$newCookie = $c->updateCookiePassword($storedCookie);
			$newList = $c->replaceOldCookie($newCookie, $oldpw);
			$this->loginView->setClientCookie($newCookie);
			$c->saveNewCookieList($newList);
		}
	}
}
