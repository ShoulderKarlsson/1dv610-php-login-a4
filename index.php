<?php
require_once('controller/MainController.php');
session_start();


/**
 * TODO: Move redirects to LoginView / RegisterView
 * TODO: Remove unused fields(?)
 */



$mc = new \controller\MainController();
return $mc->init();