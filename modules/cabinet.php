<?
session_start();
if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
	mysql_data_seek($query, 0);
	$row = mysql_fetch_assoc($query);
	$name = $row['name'];
	$content = $row['text'];
	
	$bread = breadcrumbs($row['id']);
	
/* echo "<pre>";
	print_r($_SESSION);
echo "</pre>"; */

if(isset( $_SESSION['user_id']) and $_SESSION['user_id'] != '' and $query = mysql_query("SELECT * FROM users WHERE id = '".$_SESSION['user_id']."'") and mysql_fetch_assoc($query)!=''){
/* echo "<pre>";
	print_r($_SESSION);
echo "</pre>"; */
	
	mysql_data_seek($query, 0);
	$row2 = mysql_fetch_assoc($query);
	$login = $row2['login'];
	$password = $row2['password'];
	$password2 = $row2['password2'];
	$name = $row2['name'];
	$phone = $row2['phone'];
	$email = $row2['email'];
	$address = $row2['address'];
	
	$form = '
		<div class = "warn"></div>
		<form id="registration" action="" method="POST" autocomplete="on" enctype="multipart/form-data">
			<input style = "visibility: hidden" id="c_id" type="text" value="'.$_SESSION['user_id'].'">
			<br>
			Логин
			<br>
			<input id="c_login" type="text" required pattern="[a-zA-Z0-9]{3,}" value="'.$login.'">
			<br>
			Пароль
			<br>
			<input id="c_pass" type="password">
			<br>
			Повторите пароль
			<br>
			<input id="c_pass2" type="password">
			<br>
			Имя
			<br>
			<input id="c_name" type="text" required value="'.$name.'">
			<br>
			Телефон
			<br>
			<input id="c_phone" type="text" required pattern="^\+[0-9]+$" value="'.$phone.'">
			<br>
			E-mail
			<br>
			<input id="c_email" type="email" required value="'.$email.'">
			<br>
			Адрес
			<br>
			<textarea id="c_address">'.$address.'</textarea>
			<br>
			<input type="button" id = "change_data" name="change_data" value="Изменить данные">
		</form>
	';
}else{
	 $form = '

				<h3>Пользователь не авторизован!</h3>';

}

/* 	
		if(isset($_POST['login'] , $_POST['password'] , $_POST['name'] , $_POST['phone'] , $_POST['email'])){
		
		$q = mysql_query("SELECT id FROM users WHERE login = '$login'", $connect);
		if(mysql_num_rows($q) == 0){
			
			//генерируем код активации аккаунта
			$hash = md5(time().rand(0,9999));
			
			$pass = md5($password);
			
			mysql_query("INSERT INTO users SET
				login = '$login',
				password = '$pass',
				name = '$name',
				phone = '$phone',
				email = '$email',
				address = '$address',
				hash = '$hash'
			", $connect);
			
		if(mysql_insert_id() > 0){
				
				$url_activation = 'http://'.$_SERVER['HTTP_HOST'].'/activation/?code='.$hash;
				
				$subject = "=?utf-8?B?".base64_encode("Регистрация на сайте ".$_SERVER['HTTP_HOST']."")."?=";	
				$headers = "From: ".$_SERVER['HTTP_HOST']." <".$_SERVER['HTTP_HOST'].">\r\nContent-type: text/html; charset=utf-8";  
				
				mail($email,
				$subject,
				"Здравствуйте, ".$fio."
				<br><br>
				Вы успешно зарегистрировались на сайте <a href=\"http://".$_SERVER['HTTP_HOST']."\" >".$_SERVER['HTTP_HOST']."</a>
				<br><br>
				Ваш логин: $login
				<br>
				Ваш пароль: $password
				<br><br>
				Для активации аккаунта пройдете по ссылке <a href='$url_activation'>$url_activation</a>
				<br><br>				
				Это письмо отправлено автоматической системой, отвечать на него не нужно.
				",
				$headers);
				
				$out .= '<p>Вы успешно зарегистрированы на сайте. Теперь вам нужно активировать ваш аккаунт для дальнейшей работы.</p>';
			}else{
				$out .= '<p>Ошибка регистрации. Попробуйте еще раз или обратитесь к администратору сайта.</p>'.$form;
			}	
		}else{
			$out .= '
				<p>Ошибка. Такой логин занят</p>
			'.$form;
		}
		
		
	}else{
		$out .= $form;
	}	 */

		$out .= $form;

	/* if(isset($_POST['c_pass']) and isset($_POST['c_pass2']) and $_POST['c_pass'] != $_POST['c_pass2']){
		$out .= '<p>Ошибка. Пароли не совпадают</p>'.$form;
	}elseif(isset($_POST['c_pass']) and isset($_POST['c_pass2']) and $_POST['c_pass'] == $_POST['c_pass2']){
		$out .= $form;
	} */

	$content = $out;	
	//подключаем шаблон
	include($_SERVER['DOCUMENT_ROOT'].'templates/inner.tpl');
}

?>