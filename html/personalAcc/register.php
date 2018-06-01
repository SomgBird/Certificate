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

if(empty($_POST['username']) || empty($_POST['password'])
	|| !is_array($_POST['password']) || count($_POST['password']) != 2
	|| $_POST['password'][0] !== $_POST['password'][1]
	|| empty($_POST['email']) || filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)===false 
	|| filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS)===false 
	|| filter_var($_POST['password'][0], FILTER_SANITIZE_FULL_SPECIAL_CHARS)===false
	|| filter_var( $_POST['password'][1], FILTER_SANITIZE_FULL_SPECIAL_CHARS)===false 
	|| filter_input(INPUT_POST, 'region', FILTER_SANITIZE_FULL_SPECIAL_CHARS)===false )
{
	echo "Данные введены неверно!";
	die();
}

try
{
	$dbh = new PDO("mysql:dbname={$dbName};host={$dbHost};port={$dbPort}", $dbUser, $dbPass);
}
catch(PDOException $e)
{
	echo '<p>Не получилось подключиться к базе данных</p>';
	die();
}

try
{
	$attemptedUsername = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	//$attemptedUsername = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $dbGlobalKey, $attemptedUsername, MCRYPT_MODE_CBC, $dbGlobalIv);
	
	$attemptedEmail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);
	
	$attemptedpass1 = filter_var($_POST['password'][0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$attemptedpass2 = filter_var( $_POST['password'][1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	
	$attemptedRegion = filter_input(INPUT_POST, 'region', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	
	$attemptedPhone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$attemptedPhone = preg_replace("/[^0-9]/", '', $attemptedPhone); 
	//$attemptedEmail = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $dbGlobalKey, $attemptedEmail, MCRYPT_MODE_CBC, $dbGlobalIv);
	
	$stmt = $dbh->prepare("SELECT * FROM `tblUser` WHERE `user_login`=:login_name");
	
	$stmt->bindValue(':login_name', $attemptedUsername);
	
	$stmt->execute();
		
	if($row = $stmt->fetch())
	{
		echo 'Пользователь с таким именем уже существует!';
		die();
	}
	
	$stmt = $dbh->prepare("INSERT INTO `tblUser`(`user_login`, `user_pass`, `user_email`, `region`, `user_phone_number`, `org_ID`) VALUES (:login_name, :login_pass_hash, :email, :region, :phone_number, :org_ID);");
	
	$stmt->bindValue(':org_ID', "1");
	
	$stmt->bindValue(':login_name',	$attemptedUsername);
	$stmt->bindValue(':login_pass_hash', password_hash($attemptedpass1, PASSWORD_BCRYPT, [ 'cost' => 12 ]));
	$stmt->bindValue(':email', $attemptedEmail);
		
	/*$td = mcrypt_module_open (MCRYPT_RIJNDAEL_256, "", MCRYPT_MODE_CFB, "");
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size ($td), MCRYPT_DEV_RANDOM);
	
	$stmt->bindValue(':iv',
		$iv);*/
		
	$stmt->bindValue(':region',	$attemptedRegion);
	
	$stmt->bindValue(':phone_number', $attemptedPhone);
	//echo $attemptedPhone;

	$res = $stmt->execute();
	
	if(!$res)
	{
		echo 'Ошибка при добавлении в базу данных';
		die();
	}
	
	$_SESSION['login_id'] = $dbh->lastInsertId();
	$_SESSION['login_name'] = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$_SESSION['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);
	//$_SESSION['iv'] = $iv;
	
	header('Location: index.php');
}
catch(PDOException $e)
{
	echo '<p>Ошибка с базой данных</p>';
	die();
}