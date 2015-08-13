<?

//подключение к БД
require_once($_SERVER["DOCUMENT_ROOT"]."/core/connect.php");
session_start();
//подключаем библиотеку пользовательских функций
require_once($_SERVER["DOCUMENT_ROOT"]."/core/lib/functions/_functions.php");

/*Подключаем куки*/
if(isset( $_SESSION[ 'uid' ] ) ){
	$uid = $_SESSION[ 'uid' ];
}else{
	$uid = time().rand( 1000, 99999 );
	$_SESSION[ 'uid' ] = $uid;
	/*setcookie( 'uid' , $uid , time() + 60*60*24*5 );*/
} 
$data = '';



//авторизация
if(isset($_POST['auth_login']) and $_POST['auth_login'] != '' and isset($_POST['auth_password']) and $_POST['auth_password'] != ''){
	$login = $_POST['auth_login'];
	$password = md5($_POST['auth_password']);
	$query = mysql_query("SELECT * FROM users WHERE login = '".$login."' AND password = '".$password."' AND is_new = '0' AND banned = '0'");
	if(mysql_num_rows($query) > 0){
		$row = mysql_fetch_assoc($query);
		$_SESSION['user_id'] = $row['id'];
		$_SESSION[ 'uid' ] = $row['uid'];
		
		$data = 'ok';
	}	
	echo $data;
}

?>