<?
//include($_SERVER['DOCUMENT_ROOT'].'core/lib/functions/sql_search.php');
	if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		$page_id = $row['id'];
		$name = $row['name'];
		$content = $row['text'];
		$bread = breadcrumbs($row['id']);
		$out = '';
		//подключаем шаблон
	}
	
	if(isset($_GET['search']) and $_GET['search'] != '' and strip_tags(urldecode($_GET['search'])) != ''){
		$search = strip_tags(urldecode($_GET['search']));
		$out .= sql_search("news",$search,"В новостях");
		$out .= sql_search("articles",$search,"В статьях");
		$out .= sql_search("catalog",$search,"В каталоге");
		$out .= sql_search("catalog_items",$search,"В товарах");
		$out .= sql_search("content",$search,"В текстовых разделах");
		
		if($out == ''){
			$content .= 'По данному запросу "<span>'.urldecode($_GET['search']).'</span>" ничего не найдено';
		}else{
			$content .= 'По данному запросу "<span>'.urldecode($_GET['search']).'</span>" найдено:'.$out;
		}
	}else{
		$content .= 'Поисковый запрос отсутствует.';
	}

	//подключаем шаблон		
	include($_SERVER['DOCUMENT_ROOT'].'templates/inner.tpl');

?>