<?
if($query = mysql_query("SELECT * FROM content WHERE link='404'") and mysql_fetch_assoc($query)!=''){
	mysql_data_seek($query, 0);
	$row = mysql_fetch_assoc($query);
	$name = $row['name'];
	$content = $row['text'];
	//подключаем шаблон
	include($_SERVER['DOCUMENT_ROOT'].'templates/inner.tpl');
}

?>