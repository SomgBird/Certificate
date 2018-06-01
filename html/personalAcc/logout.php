<?php
/*
 * (с) 2018 Stepanov Yuri
 * 
 * Свообдно распространяется по следующим лицензиям (по вашему желанию):
 * - GPL 3.0
 * - GFDL 1.3
 * 
 */


session_start();
define('MYSITE', 1);

require_once('config.php');

session_unset();
session_destroy();

header('Location: index.php');
