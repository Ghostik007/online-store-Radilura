<?
//подключаем все файлы из папки__halt_compiler
$path = $_SERVER['DOCUMENT_ROOT'].'/core/lib/functions/';
$array_files = scandir($path);

foreach ($array_files as $val)
{
	if($val != '_functions.php' and $val != '.' and $val != '..' and $val != '' and $val != '.htaccess')
	{
		require_once($path.$val);
	}
}