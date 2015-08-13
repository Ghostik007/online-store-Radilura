<?

//подключение к БД
require_once($_SERVER["DOCUMENT_ROOT"]."/core/connect.php");
session_start();
//подключаем библиотеку пользовательских функций
require_once($_SERVER["DOCUMENT_ROOT"]."/core/lib/functions/_functions.php");


/* echo "<pre>";
		print_r(email_cons());
echo "</pre>"; */

/*Подключаем куки*/
if(isset( $_SESSION[ 'uid' ] ) ){
	$uid = $_SESSION[ 'uid' ];
	
}else{
	$uid = time().rand( 1000, 99999 );
	$_SESSION[ 'uid' ] = $uid;
}



function email_cons(){
	$arr_cons = '';
	$query2 = '';
	$row2 = '';
	$query2 = mysql_query("SELECT * FROM users WHERE cons = '1'");
	if(mysql_num_rows($query2) > 0){
		while($row2 = mysql_fetch_assoc($query2)){
			$arr_cons['uid'][] = $row2['uid'];
			$arr_cons['email'][] = $row2['email'];
			$arr_cons['login'][] = $row2['login'];
		}
	}
	/* echo "<pre>";
		print_r($arr_cons);
	echo "</pre>";  */ 
	return $arr_cons;
}

function email_user(){
	$arr_cons = '';
	$query2 = '';
	$row2 = '';
	$query2 = mysql_query("
				SELECT 
					messages.src,  users.email, users.login
				FROM 
					messages
				LEFT JOIN 
					users
				ON 
					messages.src = users.uid
				WHERE  
					users.cons != '1'
				GROUP BY 
					messages.src
			");
	if(mysql_num_rows($query2) > 0){
		while($row2 = mysql_fetch_assoc($query2)){
			$arr_cons['uid'][] = $row2['src'];
			$arr_cons['email'][] = $row2['email'];
			$arr_cons['login'][] = $row2['login'];
		}
	}

	return $arr_cons;
}

function user_message_list(){
	$out = '';
	$arr2 = email_cons();
	$arr3 = email_user();
	if($query = mysql_query("
			SELECT
				*, DATE_FORMAT(`date`, '%H:%i:%s/%e.%m.%y') AS `date`
			FROM
				messages
			WHERE src = '".$_SESSION['uid']."'
			OR dst = '".$_SESSION['uid']."'
			ORDER BY
				UNIX_TIMESTAMP(`date`)
			DESC
		") and mysql_fetch_assoc($query) != ''){
			mysql_data_seek($query, 0);
			while($row = mysql_fetch_assoc($query)){
				if($row['src'] != $_SESSION['uid']){
					foreach($arr2['uid'] as $k => $v){
						if($row['src'] == $v){
							$row['src'] = $arr2['login'][$k];
						};
					}
					if($row['dst'] != 'to_cons'){
						$out .= '
							<div class = "response_to" style = "padding: 3px; cursor: pointer; color: #00A5FF;"> ↓↓↓ '.$row['src'].' :</div>
						';
					}else{
						$out .= '
							<div class = "response_to" style = "padding: 3px; cursor: pointer; color: #3CA65B;"> ↓↓↓ '.$row['src'].' :</div>
						';
					}
				}else{
						foreach($arr2['uid'] as $k => $v){
							if($row['dst'] == $v){
								$row['dst'] = $arr2['login'][$k];
							};
						}
						if($row['dst'] != 'to_cons'){
						$out .= '
							<div class = "response_to" style = "padding: 3px; cursor: pointer; color: #D16262;"> ↑↑↑ '.$row['dst'].' :</div>
						';
						}else{
							$out .= '
								<div class = "response_to" style = "padding: 3px; cursor: pointer; color: #3CA65B;"> ↑↑↑ '.$row['dst'].' :</div>
							';
						}
					}
				$out .= '<div>
						'.$row['date'].'
						<br>
						'.$row['text'].'
						<br><br>
					</div>
				';
			}
		}
	return $out;
}

function cons_message_list(){
	$out = '';
	$arr2 = email_user();
	$arr = email_cons();
	
	if($query = mysql_query("
			SELECT
				*, DATE_FORMAT(`date`, '%H:%i:%s/%e.%m.%y') AS `date`
			FROM
				messages
			WHERE src = '".$_SESSION['uid']."'
			OR dst = '".$_SESSION['uid']."'
			OR dst = 'to_cons'
			ORDER BY
				UNIX_TIMESTAMP(`date`)
			DESC
		") and mysql_fetch_assoc($query) != ''){
			mysql_data_seek($query, 0);
			while($row = mysql_fetch_assoc($query)){
				if($row['src'] != $_SESSION['uid'] and $row['dst'] == 'to_cons' or $row['dst'] == $_SESSION['uid']){
					foreach($arr2['uid'] as $k => $v){
						if($row['src'] == $v){
							$row['src'] = $arr2['login'][$k];
						};
					}
					foreach($arr['uid'] as $k => $v){
						if($row['src'] == $v){
							$row['src'] = $arr['login'][$k];
						};
					}

					if($row['dst'] != 'to_cons'){
						$out .= '
							<div class = "response_to" style = "padding: 3px; cursor: pointer; color: #00A5FF;"> ↓↓↓ '.$row['src'].' :</div>
						';
					}else{
						$out .= '
							<div class = "response_to" style = "padding: 3px; cursor: pointer; color: #3CA65B;"> ↓↓↓ '.$row['src'].' :</div>
						';
					}
					
				}elseif($row['src'] == $_SESSION['uid'] and $row['dst'] != $_SESSION['uid']){
					foreach($arr2['uid'] as $k => $v){
						if($row['dst'] == $v){
							$row['dst'] = $arr2['login'][$k];
						};
					}
					foreach($arr['uid'] as $k => $v){
						if($row['dst'] == $v){
							$row['dst'] = $arr['login'][$k];
						};
					}
					if($row['dst'] != 'to_cons'){
						$out .= '
							<div class = "response_to" style = "padding: 3px; cursor: pointer; color: #D16262;"> ↑↑↑ '.$row['dst'].' :</div>
						';
					}else{
						$out .= '
							<div class = "response_to" style = "padding: 3px; cursor: pointer; color: #3CA65B;"> ↑↑↑ '.$row['dst'].' :</div>
						';
					}
				}
				$out .= '
						<div>
							'.$row['date'].'
							<br>
							'.$row['text'].'
							<br><br>
						</div>
					';
			}
		}
	return $out;
}

function may_be_cons(){
	$arr = email_cons();
	$flag = false;

	foreach($arr['uid'] as $v){
		if($_SESSION['uid'] == $v){
			$flag = true;
		}
	}
 /* 	echo "<pre>";
		print_r($flag);
	echo "</pre>";  */ 
	return $flag;
}

function get_message(){
	$out = '';
	if(may_be_cons() == false){
		$out = user_message_list();

	}else{
		$out = cons_message_list();

	}
	return $out;
}

/* function get_status_cons(){
	$out = '';
	$arr2 = email_cons();
	foreach($arr2['uid'] as $v){
		if($_SESSION['uid'] == $v){
			$query = mysql_query("
			INSERT INTO 
				status_cons
			SET
				status = '1'
			");
		$out = '1';
		}	
	}
	return $out;
} */

if(isset($_POST['get'])){
	echo get_message();
}

if(isset($_POST['status'])){
	echo get_status_cons();
}


if(isset($_POST['message']) and $_POST['message'] != '' and strip_tags(urldecode($_POST['message']))){	
		$message = strip_tags(urldecode($_POST['message']));
		$arr = email_user();
		$arr2 = email_cons();
		$response_uid = '';
		$string = '';
		$string = explode(' ', $message);
		if($string[0] == 'Отвeт'){
			$message = $string;
			$message[0] = '';
			$message[1] = '';
			$message[2] = '';
			//$message[3] = '';
			$message = implode(' ', $message);
			$response_uid = $string[1];
			if($response_uid != 15 or !(preg_match('/^\d+$/',$response_uid))){
				foreach($arr2['login'] as $k => $v){
					if($response_uid == $v){
						$response_uid = $arr2['uid'][$k];
					};
				}
			}
		}
		if($response_uid != ''){
				$query = mysql_query("
					INSERT INTO 
						messages
					SET
						src = '".$_SESSION[ 'uid' ]."',
						dst = '".$response_uid."',
						text = '".$message."';
				");
		}else{
				$query = mysql_query("
					INSERT INTO 
						messages
					SET
						src = '".$_SESSION[ 'uid' ]."',
						dst = 'to_cons',
						text = '".$message."';
				");
			}
		
		echo get_message();
	}
	
if(isset($_POST['status'])){
	if($query = mysql_query("SELECT * FROM users WHERE uid = '".$_SESSION['uid']."' AND cons = '1'") and mysql_fetch_assoc($query) != ''){
		echo '1';
	}else{
		echo '0';
	}
}
?>