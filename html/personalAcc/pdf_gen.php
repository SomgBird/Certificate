<?php
session_start();

if(!isset($_SESSION['login_name'])) {
	header('Location: index.php');
}

if(isset($_FILES["file"]) || isset($_POST["message"])) {
	define('MYSITE', 1);

	require_once("./config.php");
    require_once("./CertificateCreation.php");
	require_once("./CertificateDelivery.php");

	#session_start();


	try
	{
        	$dbh = new PDO("mysql:dbname={$dbName};host={$dbHost};port={$dbPort}", $dbUser, $dbPass);
	}
	catch(PDOException $e)
	{
        	echo '<p>Не получилось подключиться к базе данных</p>';
        	die();
	}


        $result = new CertificateCreation();

        #$result->create_pdf_blank('./test_cerf.png', htmlspecialchars($_POST["name"]), htmlspecialchars($_POST["surname"]));

	if($_FILES["file"]["tmp_name"] !== "")
		if( $_FILES["file"]["type"] !== "text/plain")
			echo "Принимаем только txt!";
		else
			$result->get_list_from_file($_FILES["file"]["tmp_name"]);

	$result->get_list_from_field($_POST["message"]);

	if($_FILES["template_pic"]["tmp_name"] !== "") {
		if($_FILES["template_pic"]["type"] !== "image/png" && $_FILES["template_pic"]["type"] !== "image/jpeg") {
			echo "Некорректный шаблон! Выберите изображение в формате PNG или JPEG.";
			#echo $_FILES["template_pic"]["type"];
			die();
		} else {
			$result->create_pdf_series($_FILES["template_pic"]["tmp_name"]);
		}
	} else {
		$result->create_pdf_series("./test_cerf.png");
	}


	#$result->get_list_from_field($_POST["message"]);
	#$result->create_pdf_series($_FILES["template_pic"]["tmp_name"]);

	//echo '<html><body><a href="http://52.138.196.248/personalAcc/pdf">Сертификаты тут</a></body></html>';

	$cerf_list = $result->get_new_cerf();
	$participants = $result->get_list();
	
	if(empty($cerf_list)) {
		echo "Ввод некорректен!";
		die();
	}
	
	//Рассылка писем участникам
	foreach ($cerf_list as $key => $cerf)
		send_mail('certificatemailtest@gmail.com', $participants[$key][2], "Вы получили Сертификат(см. прикрепленные файлы), спасибо за использование наших сервисов!\nVerify_Ssl@team", 'Сертификат', $cerf);

	try {
		/* Добавляем серию в БД */
		$series_stmt = $dbh->prepare("INSERT INTO `tblRelease_series`(`ID`, `series_link`, `series_date`, `S_status`, `B_addr_check`, `event_info`) VALUES (:user_id, :series_link, :series_date, :S_status, :B_addr_check, :event_info);");

		$series_stmt->bindValue(':user_id', $_SESSION['login_id']);
		$series_stmt->bindValue(':series_link', 'test');
		$series_stmt->bindValue(':series_date', date('Y-m-d h:i:s', time()));
		
		if(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS)===false	)
		{
			echo "Данные введены неверно!";
			die();
		}
		$subj = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
		$series_stmt->bindValue(':S_status', 'test');
		$series_stmt->bindValue(':B_addr_check', 'test');
		$series_stmt->bindValue(':event_info', $subj);

		$series_res = $series_stmt->execute();

		if(!$series_res) {
			echo "Ошибка при добавлении серии";
			#print_r($series_stmt->errorInfo());
			die();
		}

		$series_id = $dbh->lastInsertId();

		/* Добавляем все сертификаты */
		#$participants = $result->get_list();

		#print_r($participants);
		#echo count($participants);
		foreach($participants as $key => $part) {
			#echo $index;
			$cerf_stmt = $dbh->prepare("INSERT INTO `tblCertificate`(`series_ID`, `certificate_link`, `C_status`, `participant_firstname`, `participant_lastname`, `participant_email`) VALUES (:series_id, :cerf_link, :C_status, :firstname, :lastname, :email);");

			$cerf_stmt->bindValue(':series_id', $series_id);
			$cerf_stmt->bindValue(':cerf_link', $cerf_list[$key]);
			$cerf_stmt->bindValue(':C_status', 'test');

			if(filter_var($part[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS)===false
				|| filter_var($part[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS)===false
				|| filter_var($part[2], FILTER_VALIDATE_EMAIL)===false )
			{
				echo "Данные введены неверно!";
				continue;
			}
			
			$firstname = filter_var($part[0], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$lastname = filter_var($part[1], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$email = filter_var($part[2], FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);

			$cerf_stmt->bindValue(':firstname', $firstname);
			$cerf_stmt->bindValue(':lastname', $lastname);
			$cerf_stmt->bindValue(':email', $email);

			$cerf_res = $cerf_stmt->execute();

			if(!cerf_res) {
				echo "Ошибка при добавлении сертификата";
				continue;
			}
			#echo "test";
			header('Location: personalArchive.php');
		}
	}
	catch (PDOException $e) {
		echo "ошибка при добавлении";
		die();
	}


} else {
	echo "Что-то упало.";
}

?>

