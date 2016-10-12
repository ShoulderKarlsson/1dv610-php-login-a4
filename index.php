<?php

require_once('controller/MainController.php');

session_start();

$mc = new \controller\MainController();
return $mc->init();

/**
 * TODO: Handle string dependencie in Cookies
 * TODO: Check if there is something that is not used
 * TODO: Move files so they cant be accsessd
 */
