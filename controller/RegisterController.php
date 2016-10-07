<?php

namespace controller;
class RegisterController {
    private $registerView;
    private $dateTimeView;
    private $flashMessageModel;
    private $layoutView;
    private $newUser;
    public function __construct(\view\RegisterView $rv, \view\LayoutView $lv, \view\DateTimeView $dtv, \model\FlashMessageModel $fm) {
        $this->registerView = $rv;
        $this->dateTimeView = $dtv;
        $this->flashMessageModel = $fm;
        $this->layoutView = $lv;
    }

    public function presentRegister($isLoggedIn) {
        $this->layoutView->renderRegister($isLoggedIn, $this->registerView, $this->dateTimeView);
    }

    public function register() {

        $this->newUser = $this->registerView->getNewUsercredentials();
        $this->userDAL = new \model\UserDAL();
        $this->users = new \model\Users($this->userDAL, $this->newUser);

        try {
            $this->users->tryToRegisterUser();
        } catch (\error\ShortPasswordException $e) {
            $this->flashMessageModel->setShortPasswordMessage();
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            // $this->flashMessageModel->setUsernameValueFlash($this->newUser->username);
            $this->redirect();
            return;

        } catch (\error\NotMatchingPasswordException $e) {
            $this->flashMessageModel->setNotMathingPasswordMessage();
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            // $this->flashMessageModel->setUsernameValueFlash($this->newUser->username);
            $this->redirect();
            return;
        } catch (\error\ShortUsernameException $e) {
            $this->flashMessageModel->setShortUsernameMessage();
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            // $this->flashMessageModel->setUsernameValueFlash($this->newUser->username);
            $this->redirect();
            return;

        } catch (\error\BusyUsernameException $e) {
            $this->flashMessageModel->setBusyUsernameMessage();
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            // $this->flashMessageModel->setUsernameValueFlash($this->newUser->username);
            $this->redirect();
            return;

        } catch (\error\ InvalidCharactersException $e) {
            $this->flashMessageModel->setInvalidCharactersMessage();
            $this->flashMessageModel->setRegisterUsernameFlash(strip_tags($this->newUser->username));
            // $this->flashMessageModel->setUsernameValueFlash(strip_tags($this->newUser->username));
            $this->redirect();
            return;
        }

        $this->users->addNewUser();
        $this->flashMessageModel->setLoginUsernameFlash($this->newUser->username);
        $this->flashMessageModel->setNewRegisterMessage();
        header('Location: /');
    }

    private function redirect() {
        header('Location: ?register');
    }
}
