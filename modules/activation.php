<?
if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
	mysql_data_seek($query, 0);
	$row = mysql_fetch_assoc($query);
	$name = $row['name'];
	$content = $row['text'];
	
	$bread = breadcrumbs($row['id']);
	
	if(isset($_GET['code'])){
		$hash = $_GET['code'];
		$query = mysql_query("SELECT id FROM users WHERE hash = '$hash'", $connect);
		if(mysql_num_rows($query) > 0){
			$row = mysql_fetch_assoc($query);
			$user_id = $row['id'];
			
			mysql_query("UPDATE users SET is_new = '0' WHERE id = '$user_id'", $connect);
			
			$out .= '
				<p>Ваша учетная запись успешно активирована.</p>
			';
		}else{
			$out .= '
				<p>Ошибка, учетная запись с таким кодом активации не найдена.</p>
			';
		}
	}else{
		$out .= '
			<p>Ошибка, код активации не передан.</p>
		';
	}
	$content .=$out;
	//подключаем шаблон
	include($_SERVER['DOCUMENT_ROOT'].'templates/inner.tpl');
}

?>