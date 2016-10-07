<?php

require_once('controller/MainController.php');

session_start();

$mc = new \controller\MainController();
return $mc->init();


- Questions - 
    Check regarding flash
    Check regarding Validation.php
