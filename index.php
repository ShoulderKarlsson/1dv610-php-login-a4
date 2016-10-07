<?php

require_once('controller/MainController.php');

session_start();

$mc = new \controller\MainController();
return $mc->init();
