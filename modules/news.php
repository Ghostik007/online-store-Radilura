<?
	if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		$page_id = $row['id'];
		$name = $row['name'];
		$content = $row['text'];
		$bread = breadcrumbs($row['id']);
		//подключаем шаблон

	}
	
	if($id == '' and $query = mysql_query("SELECT id FROM news ORDER BY UNIX_TIMESTAMP(`date`)") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);	
		while($row = mysql_fetch_assoc($query))
		{
			$perpage = 4; //4 элемента на странице
			$totalrecords = mysql_num_rows($query); //всего записей
			$numpages = ceil($totalrecords/$perpage); //всего страниц в постраничной навигации
			$cur_page = 1;
			//проверяем ограничения
			if(isset($_GET['page'])){
				$cur_page = $_GET['page'];
			}
			if(isset($cur_page)){
				if($cur_page < 1){
					$cur_page = 1;
				}
				elseif($cur_page > $numpages){
					$cur_page = $numpages;
				}
				$recordoffset = ($cur_page - 1)*$perpage;	
			}
			else{
				$cur_page = $numpages;
				$recordoffset = 0;
			}
		}
	}
	
	if($id == '' and $query = mysql_query("SELECT *, DATE_FORMAT(`date`, '%d.%m.%Y') AS `date` FROM news ORDER BY UNIX_TIMESTAMP(`date`) DESC LIMIT $recordoffset,".$perpage) and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);	
		while($row = mysql_fetch_assoc($query))
		{
			$content .= $row['date'] . '<br>
			<a href="/news/' .$row['id']. '" style="color:#000; line-height:20px;">' .
			$row['name'] . '</a><p><span>' . $row['announce'] . '</p></span><hr>'; 
		}
		
		//блок навигации
		$navigate = navigate('http://'.$_SERVER['HTTP_HOST'].'/'.$page, $cur_page, $numpages);
		$content .= $navigate;
	}
	elseif($id != '' and $query = mysql_query("SELECT *, DATE_FORMAT(`date`, '%d.%m.%Y') AS `date` FROM news WHERE id='".$id."'") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		$newsName = $row['name'];
		$content .= '
		<h2>
			'.$row['date'].'
		</h2>
		'.$row['text'];
		$bread = breadcrumbs($page_id, 'news', $row['id']);
	}
	else{
		echo '<script>location="/404/";</script>';
	}
	
	//подключаем шаблон		
	include($_SERVER['DOCUMENT_ROOT'].'templates/inner.tpl');

?>