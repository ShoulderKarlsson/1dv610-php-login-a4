<?php

require_once('controller/MainController.php');

session_start();

$mc = new \controller\MainController();
return $mc->init();

/**
 * TODO: Check if there is something that is not used
 * TODO: Move database files so they cant be accsessd
 * TODO: Try to use objects instead of array key/value pair when getting information from DB
 * TODO: Remove unused methods in Users, alot of duplicates.
 *
 * _Might need TODO this_
 * Implement interface to make it easy to switch DAL layer
 * Implement interface to make it easy to switch language for loginview / registerview
 */