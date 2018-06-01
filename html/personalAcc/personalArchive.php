<?php
session_start();

if(!isset($_SESSION['login_name'])) {
        header('Location: index.php');
}

define('MYSITE', 1);

require_once("./config.php");

try
{
	$dbh = new PDO("mysql:dbname={$dbName};host={$dbHost};port={$dbPort}", $dbUser, $dbPass);
}
catch(PDOException $e)
{
	echo '<p>Не получилось подключиться к базе данных</p>';
	die();
}

$series_stmt = $dbh->prepare("SELECT * FROM tblRelease_series WHERE ID={$_SESSION['login_id']};");

$series_stmt->execute();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Личный кабинет</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/navs.css" rel="stylesheet">
	<link href="css/personal_Archive.css" rel="stylesheet">
	
	<script src="js/showArchive.js"></script>
</head>
<body>
	<div class="col-md-12">
		<ul class="nav nav-pills">
			<li class="nav-item"><a class="nav-link" href="ProfilePage.php">Home</a></li>
			<li class="nav-item"><a class="nav-link" href="info.php">Info</a></li>
			<li class="nav-item"><a class="nav-link disabled" href="#">Help</a></li>
			<li class="nav-item"><a class="nav-link" href='logout.php'>Logout</a></li>
		</ul>
	</div>
	
	<div class="container left">
	<h2>Ваши выпущенные серии:</h2>
	<table class="table table-hover table-bordered">
		<thead>
			<tr>
				<th>Наименование выпуска</th>
				<th>Дата выпуска</th>
			</tr>
		</thead>
		<tbody>
<?php
$series_id = array();

while($series = $series_stmt->fetch(PDO::FETCH_ASSOC))
{
	$series_id[] = $series['series_ID'];
	echo "<tr onclick=\"showSeries('{$series['series_ID']}')\">";
        echo "<td>{$series['event_info']}</td>";
        echo "<td>{$series['series_date']}</td>";
        //echo "<td>{$series['S_status']}</td>";
        //echo "<td>{$series['B_addr_check']}</td>";
        //echo "<td>{$series['event_info']}</td>";
	echo "</tr>";
}

?>


		</tbody>
	</table>
	</div>

	<div class="container right">
	<h2>Сертификаты в выбранной серии:</h2>
	<table class="table table-hover table-bordered">
		<thead>
		<tr>
			<th>Имя</th>
			<th>Фамилия</th>
			<th>Email</th>
			<th>Ссылка</th>
		</tr>
		</thead>
		<tbody id="cerf_tbody">
<?php
foreach($series_id as $s_id) {
	$cerf_stmt = $dbh->prepare("SELECT * FROM tblCertificate WHERE series_ID={$s_id};");
	$cerf_stmt->execute();

	while($cerf = $cerf_stmt->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr name=\"{$s_id}\" hidden=\"true\">";
		echo "<td>{$cerf['participant_firstname']}</td>";
     		echo "<td>{$cerf['participant_lastname']}</td>";
        	echo "<td>{$cerf['participant_email']}</td>";
		$link = explode('/var/www/html', $cerf['certificate_link']);
        	echo "<td><a href=\"http://52.138.196.248{$link[1]}\">PDF</a></td>";
		echo "</tr>";
	}
}
?>
		</tbody>
	</table>
	</div>
</body>
</html>
