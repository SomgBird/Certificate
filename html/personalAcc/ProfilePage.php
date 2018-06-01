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

if(isset($_SESSION['login_name']))
{
?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Личный кабинет</title>
			<link href="css/bootstrap.min.css" rel="stylesheet">
			<link href="css/navs.css" rel="stylesheet">
			<style>
				body {
					/*background: url(css/JgmpLeuvaBQ.jpg) no-repeat;
					background-size: cover;*/ /* ширина и высота поместились в заданную область (например, окно веб-страницы) */
				}
			</style>
			<script src="js/showEntities.js"></script>
		</head>
		<body>		
			<div class="col-md-12">
				<ul class="nav nav-pills">
					<li class="nav-item"><a class="nav-link active" href="ProfilePage.php">Home</a></li>
					<li class="nav-item"><a class="nav-link" href="info.php">Info</a></li>
					<li class="nav-item"><a class="nav-link disabled" href="#">Help</a></li>
					<li class="nav-item"><a class="nav-link" href='logout.php'>Logout</a></li>
				</ul>
			</div>
				
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p align="center"><font size="6"><cite>Личный кабинет</cite></font></p>
			<p align="center"><font size="4">Добро пожаловать, <?php echo $_SESSION['login_name']; ?>!</font></p>
			<div>

				<p align="center" display: inline> <a href="create_pdf.php" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-plus"> </span> Новый выпуск</a> <a href="personalArchive.php" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-book"></span> Архив выпусков</a></p> 
				
				<p align="center"><img alt="" height="424" src="http://идея-малого-бизнеса.рф/wp-content/uploads/2016/07/6.jpg" width="636"></p>
			</div>

			<div id="actionField"></div>

		</body>
	</html>
<?php
}
else
{
?>
<?php
	header('Location: index.php');
}
