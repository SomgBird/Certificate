<?php 
/*
 * (с) 2018 
 * 
 * Свообдно распространяется по следующим лицензиям (по вашему желанию):
 * - GPL 3.0
 * - GFDL 1.3
 * 
 *  Функция отправки писем
 */
 
	include('Mail.php'); 
	include('Mail/mime.php'); 

	function send_mail($from, $to, $text, $theme, $cerf)
	{
		if(file_exists($cerf)) {
			$crlf = "\r\n"; 
			$hdrs = array(
					'From'    => $from,
					'Subject' => $theme,
                    'Content-Type' => 'text/plain; charset=UTF-8',
					"Content-Transfer-Encoding" => "8bit"
					); 

			$mime = new Mail_mime($crlf); 

			$mime->setTXTBody($text); 
			//$mime->setHTMLBody($html); 	
			$mime->addAttachment($cerf, 'text/plain; charset=UTF-8'); 
			$mimeparams=array(); 
			
			$mimeparams['text_encoding']="8bit";
			$mimeparams['text_charset']="UTF-8";
			$mimeparams['html_charset']="UTF-8";
			$mimeparams['head_charset']="UTF-8";

			$mimeparams["debug"] = "True"; 

			$body = $mime->get($mimeparams); 
			$hdrs = $mime->headers($hdrs); 

			$mail = Mail::factory('mail'); 
			$mail->send($to, $hdrs, $body); 
		}
	}
	//send_mail('certificatemailtest@gmail.com', 'certificatemailtest@gmail.com', 'qwertyui', 'testmsg', $cerf_path);
?> 
