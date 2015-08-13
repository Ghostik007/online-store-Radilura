<?
session_start();

/*Подключаем куки*/
if(isset( $_SESSION[ 'uid' ] ) ){
	$uid = $_SESSION[ 'uid' ];
}else{
	$uid = time().rand( 1000, 99999 );
	$_SESSION[ 'uid' ] = $uid;
}

if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
	mysql_data_seek($query, 0);
	$row = mysql_fetch_assoc($query);
	$name = $row['name'];
	$content = $row['text'];
	
	$bread = breadcrumbs($row['id']);
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	
	$form = '
		<form id="registration" action="" method="POST" autocomplete="on" enctype="multipart/form-data">
			<br>
			*Логин
			<br>
			<input name="login" type="text" required pattern="[a-z0-9]{3,}" value="'.$login.'">
			<br>
			*Пароль
			<br>
			<input name="password" id="reg_pass_1" type="password" required pattern="[a-z]+[A-Z]+[0-9]+[\W]+{6,}" value="'.$password.'">
			<br>
			*Повторите пароль
			<br>
			<input name="password2" id="reg_pass_2" type="password" required pattern="[a-z]+[A-Z]+[0-9]+[\W]+{6,}" value="'.$password2.'">
			<br>
			*Имя
			<br>
			<input name="name" type="text" required value="'.$name.'">
			<br>
			*Телефон в формате +79123456789
			<br>
			<input name="phone" type="text" required pattern="^\+[0-9]+$" value="'.$phone.'">
			<br>
			*E-mail
			<br>
			<input name="email" type="email" required value="'.$email.'">
			<br>
			Адрес
			<br>
			<textarea name="address">'.$address.'</textarea>
			<br>
			<input type="submit" value="Зарегистрироваться">
		</form>
	';
	
	if(isset($_POST['login'] , $_POST['password'] , $_POST['name'] , $_POST['phone'] , $_POST['email'])){
		if($_POST['password'] == $_POST['password2']){
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
					hash = '$hash',
					uid = '".$_SESSION['uid']."'
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
				$out .= '
					<p>Ошибка. Пароли не совпадают</p>
				'.$form;
		};
		
	}else{
		$out .= $form;
	}	
	
	$content = $out;
	
	//подключаем шаблон
	include($_SERVER['DOCUMENT_ROOT'].'templates/inner.tpl');
}

?>