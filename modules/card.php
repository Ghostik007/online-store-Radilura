<?
//Для карточки товара всегда должен быть определен id, 
//если это не так или id не найден - редирект на 404
if($id != '' and $query = mysql_query("SELECT * FROM catalog_items WHERE id='".$id."'") and mysql_fetch_assoc($query)!='')
{
	mysql_data_seek($query, 0);
	$row = mysql_fetch_assoc($query);
	$pid = $row['pid'];
	$good_id = $row['id'];
	$name = $row['name'];
	$text = html_entity_decode(stripslashes($row['text']));
	$price = $row['price'];
	if (is_file($_SERVER['DOCUMENT_ROOT'].'/img/'.$row['photo'])) {
		$photo = '<a href="javascript:;" title="'.$name.'" rel="lightbox-1"><img src="/img/'.$row['photo'].'" title="'.$name.'" alt="'.$name.'" width="220" border="0"></a>';
	}
	else {
		$photo = '';
	}
	
	$content = '
		<h1>'.$name.'</h1>
		<div class="card_text">
			'.$text.'			
		</div>
		<div class="card_img">
			'.$photo.'
			<br><br>
			Цена: <span class="card_price">'.$price.'</span> <span class="rub">Р</span>
			<br>
			<div class="add_cart small_button" data-id = "'.$id.'"  id="'.$id.'">
				Заказать
				<img src="/img/button_arrow.png" height="20" border="0">
			</div>
		</div>
	';

	//Получаем $page_id каталога для хлебных крошек
	$page_id = 0;
	if ($query = mysql_query("SELECT id FROM modules WHERE name='catalog' ") and mysql_fetch_assoc($query) != '')
	{
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		
		if ($query = mysql_query("SELECT id FROM content WHERE module_id='".$row['id']."'") and mysql_fetch_assoc($query) != '')
		{
			mysql_data_seek($query, 0);
			$row = mysql_fetch_assoc($query);
			$page_id = $row['id'];
		}
		
	}
	
	$bread = breadcrumbs($page_id, 'catalog', $pid, $good_id);
	include($_SERVER['DOCUMENT_ROOT'].'templates/catalog.tpl');
}