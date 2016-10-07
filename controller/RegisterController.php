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
            // $this->flashMessageModel->setShortPasswordMessage();
            $this->flashMessageModel->temp_setRegisterFlash($this->registerView->shortPasswordMessage());
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            $this->redirectToRegister();
            return;

        } catch (\error\NotMatchingPasswordException $e) {
            // $this->flashMessageModel->setNotMathingPasswordMessage();
            $this->flashMessageModel->temp_setRegisterFlash($this->registerView->notMatchingPasswordMessage());
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            $this->redirectToRegister();
            return;
        } catch (\error\ShortUsernameException $e) {
            // $this->flashMessageModel->setShortUsernameMessage();
            $this->flashMessageModel->temp_setRegisterFlash($this->registerView->shortUsernameMessage());
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            $this->redirectToRegister();
            return;

        } catch (\error\BusyUsernameException $e) {
            // $this->flashMessageModel->setBusyUsernameMessage();
            $this->flashMessageModel->temp_setRegisterFlash($this->registerView->busyUsernameMessage());
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            $this->redirectToRegister();
            return;

        } catch (\error\ InvalidCharactersException $e) {
            // $this->flashMessageModel->setInvalidCharactersMessage();
            $this->flashMessageModel->temp_setRegisterFlash($this->registerView->invalidCharactersMessage());
            // $this->flashMessageModel->setRegisterUsernameFlash(strip_tags($this->newUser->username));
            $this->flashMessageModel->setRegisterUsernameFlash(strip_tags($this->newUser->username));
            $this->redirectToRegister();
            return;
        }

        $this->users->addNewUser();
        $this->flashMessageModel->setLoginUsernameFlash($this->newUser->username);
        // $this->flashMessageModel->setNewRegisterMessage();
        $this->flashMessageModel->temp_setLoginFlash($this->registerView->newUserMessage());
        header('Location: /');
    }

    private function redirectToRegister() {
        header('Location: ?register');
    }
}
