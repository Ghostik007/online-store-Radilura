<?
if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
	mysql_data_seek($query, 0);
	$row = mysql_fetch_assoc($query);
	$name = $row['name'];
	$content = $row['text'];
	
	$bread = breadcrumbs($row['id']);
	//подключаем шаблон
	include($_SERVER['DOCUMENT_ROOT'].'templates/inner.tpl');
}

?>