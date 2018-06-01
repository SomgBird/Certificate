/*
 * (с) 2018 Yuri Stepanov
 *
 * формирование Запроса на сервер и обработка ответа.
 */

function showCell (box, id_vis) {
		var vis = (box.checked) ? "block" : "none";
		document.getElementById(id_vis).style.display = vis;
		if (!box.checked) document.getElementById('email').value = '';
}

function CreateRequest() {	
	var Request = false;
	if (window.XMLHttpRequest)
	{
		//Gecko-совместимые браузеры, Safari, Konqueror
		Request = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		//Internet explorer
		try
		{
			 Request = new ActiveXObject("Microsoft.XMLHTTP");
		}    
		catch (CatchException)
		{
			 Request = new ActiveXObject("Msxml2.XMLHTTP");
		}
	}
 
	if (!Request)
	{
		alert("Невозможно создать XMLHttpRequest");
	}
	
	return Request;
}

//Forming a request to the server
function servRequest() {
	 var xhr = CreateRequest();
	
	 //The response-waiting handler
	 xhr.onreadystatechange = function() {
	   if (xhr.readyState != 4) return;
	   
	   //button.innerHTML = "Загрузить данные";
	   document.getElementById('button').innerHTML = "Загрузить данные";
	   button.disabled = false;
	   
	   if (xhr.status != 200) {
		 // обработать ошибку
		 alert(xhr.status + ': ' + xhr.statusText);
	   } else {
			 // вывести результат
			 //alert(xhr.responseText);
			 //Старый НЕ безопасный вариант - var p_list = eval("(" + xhr.responseText + ")")
			 
			var list = document.getElementById('list');
			var li = document.createElement('LI');			
			
			var p_list = JSON.parse(xhr.responseText);
			
			//обрабатываем полученный объект JSON
			for(var p in p_list) 
			{
			  //alert(p_list[p]); 
			  if (p == "name" && p_list[p] != "off") document.getElementById('ans_name').innerHTML = "Здравствуйте, " + p_list[p] + "!";
			  if (p == "name" && p_list[p] == "off") document.getElementById('ans_name').innerHTML = "Здравствуйте, гость!";
			  
			  if (p == "email" && p_list[p] != "off") document.getElementById('ans_email').innerHTML = " Вы указали данную почту: " + p_list[p] + ".";
			  if (p == "email" && p_list[p] == "off") document.getElementById('ans_email').innerHTML = "Вы решили не указывать email.";
			  
			  if (p == "template" && p_list[p] == "on") document.getElementById('ans_template').innerHTML = "Вы загрузили новый шаблон.";
			  if (p == "template" && p_list[p] == "off") document.getElementById('ans_template').innerHTML = "Вы решили воспользоваться стандартным шаблоном.";
			  
			  if (isNumeric(p))
			  {
				  var li = document.createElement('LI');
				  li.innerHTML = p_list[p];
				  list.appendChild(li);
			  }
			}
			//alert (xhr.getAllResponseHeaders());
			//alert(p_list['name']);
			document.getElementById('person').style.display = "none";
			document.getElementById('AnsData').style.display = "block";
			
			
			document.getElementById('button').style.display = "none";
			button.disabled = false;
			document.getElementById('button_main').style.display = "block";
		}
	 }
	 
	 xhr.open('POST', 'servReq.php', true);
	 
	 //Устанавливаем заголовок
	 // xhr.setRequestHeader("Content-Type", "charset=utf-8");
	 
	 // создать объект для формы
	 var formData = new FormData(document.forms.person);

	 // добавить к пересылке ещё пару ключ - значение
	 //formData.append("patronym", "Робертович");

	 //Посылаем запрос
	 xhr.send(formData);
	 
	 button.innerHTML = 'Loading...';
	 button.disabled = true;
}

//строгая проверка на число(или строку-число)
function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
