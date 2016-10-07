<?php

namespace controller;

require_once('model/Validation.php');
require_once('model/SessionModel.php');

class RegisterController {
    private $registerView;
    private $dateTimeView;
    private $flashMessageModel;
    private $layoutView;
    private $newUser;
    public function __construct(\view\RegisterView $rv,
                                \view\LayoutView $lv,
                                \view\DateTimeView $dtv,
                                \model\FlashMessageModel $fm) {
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
        // $this->userDAL = new \model\UserDAL();
        // $this->users = new \model\Users($this->userDAL, $this->newUser);
        $this->sessionModel = new \model\SessionModel();
        $temp_validation = new \model\Validation($this->newUser, $this->sessionModel);

        try {
            // $this->users->tryToRegisterUser();
            $temp_validation->tryRegister();
        } catch (\error\ShortPasswordException $e) {
            // $this->flashMessageModel->temp_setRegisterFlash($this->registerView->shortPasswordMessage());
            $this->flashMessageModel->temp_setRegisterFlash($e->getMessage());
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            $this->redirectToRegister();
            return;

        } catch (\error\NotMatchingPasswordException $e) {
            // $this->flashMessageModel->temp_setRegisterFlash($this->registerView->notMatchingPasswordMessage());
            $this->flashMessageModel->temp_setRegisterFlash($e->getMessage());
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            $this->redirectToRegister();
            return;
        } catch (\error\ShortUsernameException $e) {
            // $this->flashMessageModel->temp_setRegisterFlash($this->registerView->shortUsernameMessage());
            $this->flashMessageModel->temp_setRegisterFlash($e->getMessage());
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            $this->redirectToRegister();
            return;

        } catch (\error\BusyUsernameException $e) {
            // $this->flashMessageModel->temp_setRegisterFlash($this->registerView->busyUsernameMessage());
            $this->flashMessageModel->temp_setRegisterFlash($e->getMessage());
            $this->flashMessageModel->setRegisterUsernameFlash($this->newUser->username);
            $this->redirectToRegister();
            return;

        } catch (\error\ InvalidCharactersException $e) {
            $this->flashMessageModel->temp_setRegisterFlash($this->registerView->invalidCharactersMessage());
            $this->flashMessageModel->setRegisterUsernameFlash(strip_tags($this->newUser->username));
            $this->redirectToRegister();
            return;
        }

        $ud = new \model\UserDAL();
        $users = new \model\Users($ud);
        $users->temp_addNewUser($this->newUser);
        // $this->users->addNewUser();
        $this->flashMessageModel->setLoginUsernameFlash($this->newUser->username);
        $this->flashMessageModel->temp_setLoginFlash($this->registerView->newUserMessage());
        header('Location: /');
    }

    private function redirectToRegister() {
        header('Location: ?register');
    }
}
