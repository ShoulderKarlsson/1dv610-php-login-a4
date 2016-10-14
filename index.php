<?php

require_once('controller/MainController.php');

session_start();

$mc = new \controller\MainController();
return $mc->init();

/**
 * TODO: Move database files so they cant be accsessd
 * TODO: Try to use objects instead of array key/value pair when getting information from DB
 * TODO: Maby move small input validation into view? [Short username, short password, not matching password and so fourth]
 *
 * TODO: Remove all temp_METHODNAME
 *
 * 
 * _Might need TODO this_
 * Implement interface to make it easy to switch DAL layer
 * Implement interface to make it easy to switch language for loginview / registerview
 */