<?
session_start();
//���������� ���� ������
include($_SERVER['DOCUMENT_ROOT'].'core/connect.php');
include($_SERVER['DOCUMENT_ROOT'].'core/lib/functions/_functions.php');

//���������� ���������� �����
include($_SERVER['DOCUMENT_ROOT'].'core/header.php');

$handler = '404';

	if(isset($module_id) and is_numeric($module_id)){
		if($query = mysql_query("SELECT name FROM modules WHERE id='".$module_id."' ") and mysql_fetch_assoc($query) != ''){
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		$handler = $row['name'];
		}
	}
	include($_SERVER['DOCUMENT_ROOT'].'modules/'.$handler.'.php');
		

//���������� ���������� �������
include($_SERVER['DOCUMENT_ROOT'].'core/footer.php');

?>