<?php

namespace controller;

require_once('model/SessionModel.php');

class RegisterController {
    private $registerView;
    private $dateTimeView;
    private $flashMessageModel;
    private $layoutView;
    private $newUser;
    private $users;
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

    public function register(\model\Users $u) {
        // $this->userDAL = new \model\UserDAL();
        // $this->users = new \model\Users($this->userDAL);

        $this->users = $u;

        try {
            return $this->tryRegisterUser();
        } catch (\error\ShortPasswordException $e) {
            $this->registerView->setShortPassword();

        } catch (\error\NotMatchingPasswordException $e) {
            $this->registerView->setNotMatchingPassword();

        } catch (\error\ShortUsernameException $e) {
            $this->registerView->setShortUsernameMessage();

        } catch (\error\BusyUsernameException $e) {
            $this->registerView->setBusyUsernameMessage();

        } catch (\error\ InvalidCharactersException $e) {
            $this->registerView->setInvalidCharactersMessage();
        }

        $this->registerView->setUsername();
        $this->renderRegister();
    }

    private function tryRegisterUser() {
        $this->newUser = $this->registerView->getNewUsercredentials();
        $this->users->searchForUsername($this->newUser);
        $this->users->addNewUser($this->newUser);

        $this->flashMessageModel->setLoginUsernameFlash($this->newUser->username);
        $this->flashMessageModel->setLoginFlash($this->registerView->newUserMessage());
        header('Location: /');
    }

    private function renderRegister() {
        $this->layoutView->renderRegister(false, $this->registerView, $this->dateTimeView);
    }
}
