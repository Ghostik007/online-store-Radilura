<?
function breadcrumbs($page_id, $table='', $item_id='', $section_id='')
{
	$out = '';
	$out_arr = array('<a href="/">Главная</a>');
	
	if ($query = mysql_query("SELECT name, link FROM content WHERE id='".$page_id."' ") and mysql_fetch_assoc($query)!='')
	{
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		$out_arr[]= ' / <a href="/'.$row['link'].'">'.$row['name'].'</a>';
		
		//если это новость или статья
		if ($item_id != '' and $table != '')
		{
			//Если это раздел каталога
			if ($table == 'catalog')
			{
				$pid = $item_id;
				$cat_arr;
				
				do {
					if ($query = mysql_query("SELECT name, id, pid FROM catalog WHERE id='".$pid."'"))
					{
						$row2 = mysql_fetch_assoc($query);
						$pid = $row2['pid'];
						$cat_arr[]= ' / <a href="/'.$row['link'].'/'.$row2['id'].'/">'.$row2['name'].'</a>';
					}
					else {
						$row2['pid'] = 0;
					}
					
				} while ($row2['pid'] != 0);
				
				$cat_arr = array_reverse($cat_arr);
				$out_arr = array_merge($out_arr, $cat_arr);
				
				//если это товар
				if ($section_id != '')
				{
					if ($query = mysql_query("SELECT name FROM catalog_items WHERE id='".$section_id."' "))
					{
						$row3 = mysql_fetch_assoc($query);
						$out_arr[]= ' / <a href="/card/'.$section_id.'">'.$row3['name'].'</a>';
					}
				}
			}
			//Если это новость
			else
			{
				$query = mysql_query("SELECT id, name FROM ".$table." WHERE id='".$item_id."' ");
				$row2 = mysql_fetch_assoc($query);
				$out_arr[]= ' / <a href="/'.$row['link'].'/'.$row2['id'].'/">'.$row2['name'].'</a>';
			}
		}
	}
	
	if (count($out_arr) > 0){
		foreach ($out_arr as $v){
			$out .= $v;
		}
	}
	return $out;
}