<?
$server = 'localhost';
$user = 'dannilg';
$password = 'eDvVhynu7UyaXLSq';
$database = 'dannilg';

$connect = mysql_pconnect($server, $user, $password);
mysql_select_db($database, $connect);
mysql_query("SET NAMES utf8");

?>