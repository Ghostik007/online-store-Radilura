<?
if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
	mysql_data_seek($query, 0);
	$row = mysql_fetch_assoc($query);
	$name = $row['name'];
	$content = $row['text'];
	//���������� ������
	include($_SERVER['DOCUMENT_ROOT'].'templates/main.tpl');
}

?>