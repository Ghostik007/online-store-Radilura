<?

//подключение к БД
require_once($_SERVER["DOCUMENT_ROOT"]."/core/connect.php");
session_start();
//подключаем библиотеку пользовательских функций
require_once($_SERVER["DOCUMENT_ROOT"]."/core/lib/functions/_functions.php");

$out = '';
if(isset($_POST['curpage']) and isset($_POST['pid'])){
	if($_POST['from_price'] == '' and $_POST['between_price'] == ''){
		if($_POST['sort_by'] == '1'){
			$sort = 'ASC';
		}else{
			$sort = 'DESC';
		}
	
	if($query = mysql_query("SELECT id FROM catalog_items WHERE pid='".$_POST['pid']."' ORDER BY pos ASC") and mysql_fetch_assoc($query)!=''){
		//$out = '11111';
		$perpage = 12;
		$totalrecords = mysql_num_rows($query);
		$numpages = ceil($totalrecords/$perpage);
		$cur_page = $_POST['curpage'];
		
		//Проверяем ограничения
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
				<input id = "pid" value = "'.$pid.'">
				<input id = "recordoffset" value = "'.$recordoffset.'">
				<div id = "goods_list_icon" title = "список"></div>
				<div id = "goods_grid_icon" title = "сетка"></div>
				<div id = "list_filter">Сортировать:
					<select id = "sort_by">
					  <option selected value = "1">по убыванию</option>
					  <option value = "2">по возрастанию</option>
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
		
		if($query = mysql_query("SELECT * FROM catalog_items WHERE pid='".$_POST['pid']."' ORDER BY pos ASC LIMIT $recordoffset, $perpage") 
		and mysql_fetch_assoc($query)!='') 
		{
			//$out .= $catalog_filter;
			$out .= '<div id = "all_goods_list">';
			$navigate = navigate($cur_page, $numpages);
			$out .= $navigate;
			
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
				
				
				$out .= '
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

			$out .= '</div>';
			}
		}
	}
}
echo $out;
?>