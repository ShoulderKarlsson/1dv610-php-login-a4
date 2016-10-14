<?php

namespace controller;

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
        $this->userDAL = new \model\UserDAL();
        $this->users = new \model\Users($this->userDAL);

        try {
            $this->newUser = $this->registerView->getNewUsercredentials();
            $this->users->searchForUsername($this->newUser);

        } catch (\error\ShortPasswordException $e) {
            $this->registerView->temp_setShortPassword();
            $this->registerView->temp_setUsername();
            $this->layoutView->renderRegister(false, $this->registerView, $this->dateTimeView);
            return;

        } catch (\error\NotMatchingPasswordException $e) {
            $this->registerView->temp_setNotMatchingPassword();
            $this->registerView->temp_setUsername();
            $this->layoutView->renderRegister(false, $this->registerView, $this->dateTimeView);
            return;            

        } catch (\error\ShortUsernameException $e) {
            $this->registerView->temp_setUsername();
            $this->registerView->temp_shortUsernameMessage();
            $this->layoutView->renderRegister(false, $this->registerView, $this->dateTimeView);
            return;

        } catch (\error\BusyUsernameException $e) {
            $this->registerView->temp_busyUsernameMessage();
            $this->registerView->temp_setUsername();
            $this->layoutView->renderRegister(false, $this->registerView, $this->dateTimeView);
            return;

        } catch (\error\ InvalidCharactersException $e) {
            $this->registerView->temp_invalidCharactersMessage();
            $this->registerView->temp_setUsername();
            $this->layoutView->renderRegister(false, $this->registerView, $this->dateTimeView);
            return;

        }

        // Hello?! This is done in the examt same method...........
        $ud = new \model\UserDAL();
        $users = new \model\Users($ud);
        $users->temp_addNewUser($this->newUser);
        
        $this->flashMessageModel->setLoginUsernameFlash($this->newUser->username);
        $this->flashMessageModel->temp_setLoginFlash($this->registerView->newUserMessage());
        header('Location: /');
    }

    // // Not using?
    // private function redirectToRegister() {
    //     header('Location: ?register');
    // }
}
