<?php
/*
 * (с) 2018 Stepanov Yuri
 * 
 * Свообдно распространяется по следующим лицензиям (по вашему желанию):
 * - GPL 3.0
 * - GFDL 1.3
 * 
 */


define('MYSITE', 1);

require_once('config.php');

session_start();

try
{
	$dbh = new PDO("mysql:dbname={$dbName};host={$dbHost};port={$dbPort}", $dbUser, $dbPass);
}
catch(PDOException $e)
{
	echo '<p>Не получилось подключиться к базе данных</p>';
	echo $e->getMessage();
	die();
}

try
{
	$attemptedUsername = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	//MCRYPT_RIJNDAEL_128 - алгоритм шифрования, MCRYPT_MODE_CBC - мода алгоритма(что-то между блоками)
	//$attemptedUsername = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $dbGlobalKey, $attemptedUsername, MCRYPT_MODE_CBC, $dbGlobalIv);

	$stmt = $dbh->prepare("SELECT * FROM `tblUser` WHERE `user_login`=:login_name");
	
	$stmt->bindValue(':login_name', $attemptedUsername);
	
	$stmt->execute();
		
	if(($row = $stmt->fetch()) && password_verify($_POST['password'], $row['user_pass']))
	{
		$_SESSION['login_name'] = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS); //$attemptedUsername;
		$_SESSION['login_id'] = $row['ID'];
		$_SESSION['email'] = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $dbGlobalKey, $row['user_email'], MCRYPT_MODE_CBC, $dbGlobalIv);;
		
		header('Location: index.php');
	}
	else
	{
		echo 'Не верные данные';
	}
}
catch(PDOException $e)
{
	echo '<p>Ошибка!</p>';
}