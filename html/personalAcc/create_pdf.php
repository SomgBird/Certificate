<!DOCTYPE html>
<html>
<head>
	<title>Новый выпуск</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
		
	<link href="css/navs.css" rel="stylesheet">
	<script src="js/showEntities.js"></script>
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
		
	<!-- V1.0 -->
	<form class="form-horizontal" id="pdfGenForm1" action="pdf_gen.php" method="post" enctype="multipart/form-data">
		<fieldset>

		<!-- Form Name -->
		<legend><h1>Новый выпуск</h1></legend>

		<!-- Text input-->
		<div class="form-group" >
		  <label class="col-md-4 control-label" for="subject"><big>Введите название выпуска</big></label>  
		  <div class="col-md-4">
			<input id="Введите название выпуска" name="subject" type="text" placeholder="Название нового выпуска" class="form-control input-md" required="">
			<br>
			<span class="help-block">Организуйте записи списка в следующем виде(<em>все некорректные записи будут проигнорированы</em>): <br>Иван Иванов Ivanoff@yandex.ru<br>Антон Антонов Antonoff@gmail.com</span>  
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group" >
		  <label class="col-md-4 control-label" for="textinput"><big>Введите список участников или выберите файл</big></label>  
		  <div class="col-md-4">
			  <div class="form-group">             
				<textarea class="form-control" name="message" id="message" cols="40" rows="3" placeholder="Список участников:"></textarea>
			  </div>
		  </div>
		</div>

		<!-- File Button --> 
		<div class="form-group" >
		  <label class="col-md-4 control-label" for="filebutton">Выберите файл со списком участников</label>
		  <div class="col-md-4">
			<input type="file" name="file" id="file" accept="text/plain" class="input-file">
		  </div>
		</div>
		
		<!-- File Button --> 
		<div class="form-group">
		  <label class="col-md-4 control-label" for="filebutton"><big>Выберите свой шаблон сертификата</big></label>
		  <div class="col-md-4">
			<input id="template_pic" name="template_pic" class="input-file" accept="image/*" type="file">
		  </div>
		</div>


		<!-- Button -->
		<div class="form-group" >
		  <label class="col-md-4 control-label" for="singlebutton"></label>
		  <div class="col-md-4">
			<button type='submit' class="btn btn-primary">Начать Выпуск</button>
		  </div>
		</div>

		</fieldset>
	</form>
</body>
<html>
