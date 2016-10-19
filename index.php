<?php
require_once('controller/MainController.php');
session_start();


/**
 * TODO: Move redirects to LoginView / RegisterView
 */



$mc = new \controller\MainController();
return $mc->init();