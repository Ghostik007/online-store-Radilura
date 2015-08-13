<?
	if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		$page_id = $row['id'];
		$name = $row['name'];
		//$content = $row['text'];
		$bread = breadcrumbs($row['id']);

		//подключаем шаблон

	}

	//Выводим корневые разделы каталога (pid=0)
	if($id == '' and $query = mysql_query("SELECT * FROM catalog WHERE pid='0' ORDER BY pos ASC") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);
		
		while($row = mysql_fetch_assoc($query)){
			//Проверяем есть ли фотография
			if(is_file($_SERVER['DOCUMENT_ROOT'].'/img/'.$row['photo'])){
				$img = '<img src="/img/'.$row['photo'].'">';
			}
			else{
				$img = '';
			}
			$content .= '
			<a href="/'.$page.'/'.$row['id']. '" class="section">
				<div class="section_img"> 
					'.$img.'
				</div>
				<div class="section_name">' 
					.$row['name'].'
				</div>
			</a>
			'; 
		}
		//Товары в корневой директории не выводим. Их тут нет.
	}
	elseif($id != '' and $query = mysql_query("SELECT * FROM catalog WHERE id='".$id."'") and mysql_fetch_assoc($query)!='')
	{
		//Раздел каталога со списком подразделов и товаров (если они есть), т.е существует id  и он не пустой
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		//для товаров и разделов - это родительский идентификатор
		$pid = $row['id'];
		
		$content .= '
		<h1>
			'.$row['name'].'
		</h1>
		<div class="text">
			'.html_entity_decode(stripslashes($row['tExt'])).'
			<div class="clear"></div>
		</div>
		';
		
		//Выводим подразделы если есть
		if($query = mysql_query("SELECT * FROM catalog WHERE pid='".$pid."' ORDER BY pos ASC") and mysql_fetch_assoc($query)!=''){
			mysql_data_seek($query, 0);
			while($row = mysql_fetch_assoc($query)){
				//Проверяем есть ли фотография
				if(is_file($_SERVER['DOCUMENT_ROOT'].'img/'.$row['photo'])){
					$img = '<img src="/img/'.$row['photo'].'" border="0">';
				}
				else{
					$img = '';
				}
				$subName = $row['name'];
				$content .= '
				<a href="/'.$page.'/'.$row['id'].'" class="section">
					<div class="section_img">
						'.$img.'
					</div>
					<div class="section_name">
						'.$row['name'].'
					</div>
				</a>
				';
			}
		}
		
		
		//Выполняем расчёты для постранички
		//Выводим товары раздела, если они есть
		if($query = mysql_query("SELECT id FROM catalog_items WHERE pid='".$pid."' ORDER BY pos ASC") and mysql_fetch_assoc($query)!='')
		{
			$perpage = 12;
			$totalrecords = mysql_num_rows($query);
			$numpages = ceil($totalrecords/$perpage);
			$cur_page = 1;
			
			//Проверяем ограничения
			if (isset($_GET['page'])) {
				$cur_page = $_GET['page'];
			}
			if (isset($cur_page)) {
				if ($cur_page < 1){
					$cur_page = 1;
				}elseif ($cur_page > $numpages){
					$cur_page = $numpages;
				}
				$recordoffset = ($cur_page - 1) * $perpage;
			}else {
				$cur_page = $numpages;
				$recordoffset = 0;
			}
			
			$catalog_filter = '
				<div id = "catalog_filter">
					<input class = "hidden_input" id = "pid" value = "'.$pid.'">
					<input class = "hidden_input" id = "recordoffset" value = "'.$recordoffset.'">
					<input class = "hidden_input" id = "hcurpage" value = "1">
					<input class = "hidden_input" id = "endpage" value = "'.$numpages.'">
					<div id = "goods_list_icon" title = "список"></div>
					<div id = "goods_grid_icon" title = "сетка"></div>
					<div class = "list_filter">Сортировать:
						<select class = "sort_select" id = "sort_type">
						  <option selected value = "1">по позиции</option>
						  <option value = "2">по цене</option>
						</select>
					</div>
					<div class = "list_filter">Упорядочить:
						<select class = "sort_select" id = "sort_by">
						  <option selected value = "1">по возрастанию</option>
						  <option value = "2">по убыванию</option>
						</select>
					</div>
					<div id = "price_filter">Цена от: 
						<input class = "input_price" id = "from_price" type = text pattern="[0-9]">  до: 
						<input class = "input_price" id = "between_price" type = text>
					</div>

					<div id = "button_filter">Применить</div>
					<div class = "clear"></div>
				</div>
			';
			
			if($query = mysql_query("SELECT * FROM catalog_items WHERE pid='".$pid."' ORDER BY pos ASC LIMIT $recordoffset, ".$perpage) 
			and mysql_fetch_assoc($query)!='') 
			{
				$content .= $catalog_filter;
				$content .= '<div id = "all_goods_list">';
				$navigate = navigate($cur_page, $numpages);
				$content .= $navigate;
				
				mysql_data_seek($query, 0);
				while ($row = mysql_fetch_assoc($query))
				{
					if (is_file($_SERVER['DOCUMENT_ROOT'].'/img/'.$row['photo']))
					{
						$img = '<img src="/img/'.$row['photo'].'" width="150" border="0">';
					}
					else
					{
						$img = '';	
					}
					
					
					$content .= '
					<div class="goods">
						<a href="/card/'.$row['id'].'" id="goods_name" title="'.$row['name'].'">
							'.$row['name'].'
						</a>
						<a href="/card/'.$row['id'].'" title="'.$row['name'].'" class="goods_img">
							<img src="/img/'.$row['photo'].'" title="'.$row['name'].'" alt="'.$row['name'].'" width="150" border="0">
						</a>
						<div class="price">
							'.$row[ 'price' ].' Р.
						</div>
						<div class="anons">
							'.$row['anons'].'
						</div>
						<div class="add_cart small_button" data-id = "'.$row['id'].'" id= "'.$row['id'].'">
							Заказать
							<img src="/img/button_arrow.png" height="20" border="0">
						</div>
					</div>
					';
				}
				
				
				//Блок навигации

				$content .= '</div>';
			}
		}
		
		$bread = breadcrumbs($page_id, 'catalog', $pid);
	}
	else{
		echo '<script>location="/404/";</script>';
	}
	
	
	

	//подключаем шаблон		
	include($_SERVER['DOCUMENT_ROOT'].'templates/catalog.tpl');
?>