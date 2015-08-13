<?
$phone = '';
	if($query = mysql_query("SELECT * FROM options WHERE name='phone'") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		$phone = $row['value'];
	}	
$email = '';
if($query = mysql_query("SELECT * FROM options WHERE name='e-mail'") and mysql_fetch_assoc($query)!=''){
	mysql_data_seek($query, 0);
	$row = mysql_fetch_assoc($query);
	$email = $row['value'];
}
$address = '';
if($query = mysql_query("SELECT * FROM options WHERE name='address'") and mysql_fetch_assoc($query)!=''){
	mysql_data_seek($query, 0);
	$row = mysql_fetch_assoc($query);
	$address = $row['value'];
}
$copyright = '';
if($query = mysql_query("SELECT * FROM options WHERE name='copyright'") and mysql_fetch_assoc($query)!=''){
	mysql_data_seek($query, 0);
	$row = mysql_fetch_assoc($query);
	$copyright = $row['value'];
}

include($_SERVER['DOCUMENT_ROOT'].'/templates/footer.tpl');

?>