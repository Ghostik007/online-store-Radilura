<?
function sql_search($name,$search,$where = ''){
	$out = '';
	
	if($query = mysql_query("SELECT * FROM ".$name." WHERE name LIKE '%".trim($search)."%' OR text LIKE '%".trim($search)."%'") AND mysql_fetch_assoc($query) != ''){
		mysql_data_seek($query,0);
		
		if($where != ''){
			$out .= '<a href = "/'.$name.'/" style = "text-decoration: none;"><h2 style = "color:#8c8c8c8">'.$where.'</h2></a>';
		}
		while($row = mysql_fetch_assoc($query)){
			$page_id = $row['id'];
			$text = stripslashes(html_entity_decode($row['text']));
			$text = strip_tags($text);
			$text = substr($text,0,150).'...';
			// обрезка по последнему слову...
			if($data = explode(" ",$text)){
				$text = '';
				$dc = count($data);
				$cc = 0;
				foreach($data as $v){
					$cc++;
					if($cc < $dc){
						$text .= ' '.$v;
					}
				}
				$text .= '...';
			}
			//Для динамических страниц добавляем идентификатор
			if($name == 'content'){
				if($query2 = mysql_query("SELECT * FROM content WHERE id = '".$page_id."'") and mysql_fetch_assoc($query2) != ''){
					mysql_data_seek($query2,0);
					$row2 = mysql_fetch_assoc($query2);
					$out .='
					<i>'.$text.'</i>
					<br>
					<a href = "/'.$row2['link'].'/">'.stripslashes($row2['name']).'</a>
					<br>
					<div class = "clear" style = "margin-top: 10px; margin-bottom: 10px; border-bottom: 1px solid #ececec"></div>
					';
				}
			}else{
				if($name == 'catalog_items'){
					$name = 'card';
				}
				$out .= '
					<i>'.$text.'</i>
					<br>
					<a href = "/'.$name.'/'.$row['id'].'">'.stripslashes($row['name']).'</a>
					<br>
					<div class = "clear" style = "margin-top: 10px; margin-bottom: 10px; border-bottom: 1px solid #ececec"></div>
				';
			}
		}
	}
	return $out;
};
?>