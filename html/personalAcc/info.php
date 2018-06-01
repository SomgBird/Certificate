<?php
/*
 * (с) 2018 Stepanov Yuri, Lukashov Timofey
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
		<title>Информация</title>
		
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
		
		<link href="css/navs.css" rel="stylesheet">
		<link href="css/info.css" rel="stylesheet">
		<script src="js/showEntities.js"></script>
	</head>
	<body>		
		<div class="col-md-12">
			<ul class="nav nav-pills">
				<li class="nav-item"><a class="nav-link" href="ProfilePage.php">Home</a></li>
				<li class="nav-item"><a class="nav-link active" href="info.php">Info</a></li>
				<li class="nav-item"><a class="nav-link disabled" href="#">Help</a></li>
				<li class="nav-item"><a class="nav-link" href='logout.php'>Logout</a></li>
			</ul>
		</div>
			
		<h2><span>О программе</span></h2>
		<p>Наша система предоставляет пользователям возможность генерации и предоставления подписанных цифровых объектов.</p>
		
		<h2><span>Страница авторизации/регистрации</span></h2>
		<p><img alt="LoginPage12.jpg" src="https://se.cs.petrsu.ru/w/images/thumb/2/26/LoginPage12.jpg/494px-LoginPage12.jpg" width="350" height="425"/><br />
		</p>
		<ul>
			<li><b> Кнопка "Войти" </b>: По нажатию кнопки открывается личный кабинет пользователя, если вы уже зарегистрированы в системе.<br />
			</li>
			<li><b> Кнопка "Отправить" </b>: По нажатию кнопки введенные данные заносятся в БД и пользователь перенаправляется в личный кабинет.
			</li>
		</ul>
		
		<h2><span>Личный кабинет</span></h2>
		<p><img alt="LichKab.jpg" src="https://se.cs.petrsu.ru/w/images/d/d4/Lichkab1.jpg" width="500" height="234"/><br /></p>
		<ul>
			<li><b> Кнопка "Новый выпуск" </b>: По нажатию кнопки открывается окно создания нового выпуска сертификатов.<br /></li>
			<li><b> Кнопка "Архив сертификатов" </b>: По нажатию кнопки открывается окно со всеми созданными пользователем сертификатами.<br /></li>
			<li><b> Кнопка "Logout" </b>: По нажатию кнопки пользователь выходит из системы.<br /></li>
			<li><b> Кнопка "Info" </b>: По нажатию кнопки открывается окно с информацией о системе.<br /></li>
			<li><b> Кнопка "Help" </b>: По нажатию кнопки открывается окно Технической поддержки.<br /></li>
		</ul>
		<h2><span>Новый выпуск</span></h2>
		<p><img alt="NovVip2.jpg" src="https://se.cs.petrsu.ru/w/images/b/b3/NovVip2.jpg" width="350" height="425"/><br /></p>
		<ul>
			<li><b> Поле "Название нового выпуска" </b>: Предназначено для именования нового выпуска.<br /></li>
			<li><b> Поле "Список участников" </b>: Предназначена для ввода списка участников (Каждый участник на новой строке!).<br /></li>
			<li><b> Кнопка "Выберете файл" со списком участников </b>: По нажатию кнопки открывается окно выбора файла со списком участников.<br /></li>
			<li><b> Кнопка "Выберете файл" шаблона сертификата </b>: По нажатию кнопки открывается окно выбора файла с шаблоном сертификата.<br /></li>
			<li><b> Кнопка "Начать выпуск" </b>: По нажатию кнопки открывается страница архива с отображением текущей серии.<br /></li>
		</ul>
		<h2><span>Архив выпусков</span></h2>
		<p><img alt="ArchivePage1.jpg" src="https://se.cs.petrsu.ru/w/images/d/d8/Archivepage1.jpg" width="600" height="234"/><br /></p>
		<ul>
			<li><b> Таблица "Ваши выпущенные серии" </b>: Предназначена для отображения ваших выпусков.<br /></li>
			<li><b> Клик на поле таблицы </b>: Отображает сертификаты в выбранной серии.<br /></li>
			<li><b> Таблица "Сертификаты в выбранной серии" </b>: Предназначена для отображения сертификатов в выбранной пользователем серии.<br /></li>
			<li><b> Ссылка "PDF" </b>: По нажатию на ссылку открывается выпущенный пользователем сертификат.<br /></li>
		</ul>
		<h2><span>Info</span></h2>
		<p><img alt="Infostran1.jpg" src="https://se.cs.petrsu.ru/w/images/4/47/Infostran1.jpg" width="600" height="234"/><br /></p>
		<p><img alt="Infostran2.jpg" src="https://se.cs.petrsu.ru/w/images/7/74/Infostran2.jpg" width="600" height="234"/><br /></p>
		<ul>
			<li><b> Страница "Info" </b>: Предназначена для отображения информации о сервисе.<br /></li>
		</ul>
		<h2><span>Help</span></h2>
		<b> Техническая поддержка </b> (Не реализована)
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