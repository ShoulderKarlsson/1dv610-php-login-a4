<?php

require_once('controller/MainController.php');

session_start();

$mc = new \controller\MainController();
return $mc->init();

/**
 * TODO: Handle string dependencie in Cookies
 * TODO: Check if there is something that is not used
 * TODO: Move validation into NewUser and User - This can work since not using validation class
 * 		 Users -> send it in as parameters, each user instead of doing it in ctor
 * TODO: Move files so they cant be accsessd
 */
