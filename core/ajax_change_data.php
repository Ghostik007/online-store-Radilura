<?

//подключение к БД
require_once($_SERVER["DOCUMENT_ROOT"]."/core/connect.php");
session_start();
$password = '';


if(isset($_POST['make_change']) and $_POST['make_change'] == 1){
	if($query = mysql_query("SELECT * FROM users WHERE email='".$_POST['c_email']."' AND id != '".$_POST['c_id']."'") and mysql_fetch_assoc($query) == ''){
		$query = mysql_query("
				UPDATE  users 
				SET	login = '".$_POST['c_login']."', 		
					name = '".$_POST['c_name']."',
					email = '".$_POST['c_email']."',
					phone = '".$_POST['c_phone']."',
					address = '".$_POST['c_address']."'
				WHERE  id = '".$_POST['c_id']."'"
		);
		echo '1';
	}else{
		echo '0';
	}
}elseif(isset($_POST['make_change']) and $_POST['make_change'] == 2){
	if($query = mysql_query("SELECT * FROM users WHERE email='".$_POST['c_email']."' AND id != '".$_POST['c_id']."'") and mysql_fetch_assoc($query) == ''){
		$password = md5($_POST['c_pass']);
		$query = mysql_query("
				UPDATE  users 
				SET	login = '".$_POST['c_login']."',
					password = '".$password."',					
					name = '".$_POST['c_name']."',
					email = '".$_POST['c_email']."',
					phone = '".$_POST['c_phone']."',
					address = '".$_POST['c_address']."'
				WHERE  id = '".$_POST['c_id']."'"
		);
		echo '1';
	}else{
		echo '0';
	}
}

?>
