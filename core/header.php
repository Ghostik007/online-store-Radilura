<?php
ob_start();
?>
<script>
	function get_messages(){
		$.post('/core/ajax_messenger.php',{get: 1}, function(data, status){
			if(status == "success" && data != ''){
				$('#area_chat').html(data);
			}
		});
	}
	
	function show_open_cart_button(){
				$('#open_cart').show();
	};
</script>
<?
$popup = '
	<div class = "popup"></div>;
';

//Обработчик запросов к сайту для получения контента страниц
$data = $_SERVER['REQUEST_URI'];
$left_menu = '';
$news_menu = '';
$articles_menu = '';
$uri = explode('/', $data);
$page = $uri[1];
//Параметр id, если он есть (пример /news/1 - новость с id=1)
$id = '';
	if(isset($uri[2]) and is_numeric($uri[2])){
		$id = $uri[2];
	}	
	
//Боковое левое меню Каталог

	if($query = mysql_query("SELECT * FROM catalog WHERE pid='0' ORDER BY pos ASC") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);
		while($row = mysql_fetch_assoc($query)){
			$id0 = $row['id'];
			$left_menu .= '<div class = "section_menu_container">
			<a class="section_menu" href="/catalog/'.$row['id']. '" title="'.$row['name'].'">
				'.$row['name'].'
			</a>
			'; 
			
			if($query2 = mysql_query("SELECT * FROM catalog WHERE pid='".$id0."'") and mysql_fetch_assoc($query2)!=''){
				mysql_data_seek($query2, 0);
				while($row2 = mysql_fetch_assoc($query2)){
					$left_menu .= '
					<a class="subsection_menu" href="/catalog/'.$row2['id']. '" title="'.$row2['name'].'">
						'.$row2['name'].'
					</a>
					'; 
				}
			}
			$left_menu .= '</div>';
		}
		
	}
	
//Боковое правое меню Новости
	if($query = mysql_query("SELECT *, DATE_FORMAT(`date`, '%d.%m.%Y') AS `date` FROM news ORDER BY UNIX_TIMESTAMP(`date`) DESC LIMIT 0,3") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);	
		while($row = mysql_fetch_assoc($query))
		{
			$news_menu .= '
				<div class="news_element">
				<div class="date">'.$row['date'].'</div>
				'.$row['name'].'
				<a href="/news/' .$row['id'].'">подробнее...</a>
			</div>
			';
		}
	}
	
//Боковое правое меню Статьи
	if($query = mysql_query("SELECT *, DATE_FORMAT(`date`, '%d.%m.%Y') AS `date` FROM articles ORDER BY UNIX_TIMESTAMP(`date`) DESC LIMIT 0,3") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);	
		while($row = mysql_fetch_assoc($query))
		{
			$articles_menu .= '
				<a class="article" href="/articles/'.$row['id'].'">'.$row['name'].'</a>
			';
		}
	}


	if($page != ''){
	//Все страницы кроме главной
		if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
			mysql_data_seek($query, 0);
			$row = mysql_fetch_assoc($query);
			$title = $row['title'];
			$description = $row['description'];
			$keywords = $row['keywords'];
			$module_id = $row['module_id'];
		}
	 	else{
			//Ошибка 404, module_id=3
			if($query = mysql_query("SELECT * FROM content WHERE module_id='3'") and mysql_fetch_assoc($query)!=''){
				mysql_data_seek($query, 0);
				$row = mysql_fetch_assoc($query);
				$title = $row['title'];
				$description = $row['description'];
				$keywords = $row['keywords'];
				$module_id = $row['module_id'];
			} 
		}
		
		// Передаем уникальные мета-теги в динамические страницы:
		// Во все страницы, кроме карточки товара
		if($id != '' and $query = mysql_query("SELECT * FROM ".$page." WHERE id='".$id."' AND title != '' ") and mysql_fetch_assoc($query) != '' and $page != 'card'){
			mysql_data_seek($query, 0);
			$row = mysql_fetch_assoc($query);
			$title = $row['title'];
			$description = $row['description'];
			$keywords = $row['keywords'];
			
		// В карточку товара
		}elseif($id != '' and $query = mysql_query("SELECT * FROM catalog_items WHERE id='".$id."' AND title != '' ") and mysql_fetch_assoc($query) != ''){
			mysql_data_seek($query, 0);
			$row = mysql_fetch_assoc($query);
			$title = $row['title'];
			$description = $row['description'];
			$keywords = $row['keywords'];
		}
			
	}else{
		if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
			mysql_data_seek($query, 0);
			$row = mysql_fetch_assoc($query);
			$title = $row['title'];
			$description = $row['description'];
			$keywords = $row['keywords'];
			$module_id = $row['module_id'];
		}
	}
	
	

	
	if($query = mysql_query("SELECT * FROM options WHERE name='phone'") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		$phone = $row['value'];
	}	

	if(isset($_SESSION['uid'])){
		$uid = $_SESSION['uid'];
	}else{
		$uid = time().rand(1000, 99999);
		$_SESSION['uid'] = $uid;
	}
	
	
	//формируем вывод мини корзины
	$mini_cart = '';
	$gmini_cart_card = '';
	$query = mysql_query( "
	SELECT  	c.id AS id,
				c.quantity AS quantity,
				ci.price AS price,
				c.good_id AS good_id,
				ci.name AS name,
				ci.photo AS photo,
				ci.anons AS anons
	FROM 		cart AS c
	LEFT JOIN	catalog_items AS ci
	ON			c.good_id = ci.id
	WHERE 		uid = '$uid'
	");
	
	$sum = 0;
	$quantity = 0;
	$good_id = 0;
	
	if(mysql_num_rows($query) > 0){
		$gmini_cart_card .= '
			<script>
				$(function(){
					show_open_cart_button();
				});
			</script>
		';

		while($row = mysql_fetch_assoc($query)){
			$quantity += $row['quantity'];
			$sum += $row['quantity']*$row['price'];
			$goods_sum = $row['quantity']*$row['price'];
			$gmini_cart_card .= '
				<tr>
					<td>
						<div class = "good_descript_window">
							<span class = "good_descript">'.$row['name'].'</span>
							<a href="/card/'.$row['good_id'].'" title="'.$row['name'].'" class="goods_img">
								<img src="/img/'.$row['photo'].'" title="'.$row['name'].'" alt="'.$row['name'].'" height="50px" border="0">
							</a>
							<span class = "good_descript">'.$row['price'].' Р.</span>
							<div class = "del_position_gmini_cart" data-id = "'.$row['good_id'].'" title = "Убрать позицию">X</div>
						</div>
					</td>
					<td>
						<span class = "good_descript">X'.$row['quantity'].'</span>
					</td>
					<td>
						<span style = "font-weight: bold; font-size: 1.2em">
							'.$goods_sum.' Р.
						</span>
					</td>
				</tr>
				
			';
		}
	}
	$sum = number_format($sum, 2, ',', ' ');
	$mini_cart .= '
		Товаров: <span>'.$quantity.' шт.</span> на сумму: <span>'.$sum.' руб.</span>
	';
	
	
//вывод формы для авторизации
$auth = '
	<input type="text" id="auth_login" placeholder="логин">
	<input type="password" id="auth_password" placeholder="******">
	<div class="button_auth"></div>
	<div class="clear"></div>
	<a href="/forgot_password/">Забыли пароль</a>
	<a href="/register/">Регистрация</a>
';

if(isset($_SESSION['user_id']) AND $_SESSION['user_id'] != '' AND $query = mysql_query("SELECT * FROM users WHERE id = '".$_SESSION['user_id']."'") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
/* 		echo "<pre>";
			print_r($_SESSION);
		echo "</pre>";  */
		
		$user_name = $row['name'];

		$auth = '
			<div><br>'.$user_name.'</br></div>
			<a href="/cabinet/">Личный кабинет</a>
			<a href="/cabinet/?support">Менеджер</a>
			<a href="?logout">Выход</a>
		';
	}
	
$gmini_cart = '
	<div id = "open_cart" title = "Развернуть/скрыть корзину">+</div>
	<div id = "gmini_cart">
		<table class = "table table-striped" cellspacing = "0">
				<tr>
					<td><span class = "good_descript">Описание</span></td><td><span class = "good_descript">шт.</span></td><td><span class = "good_descript">Сумма</span></td>
				</tr>	
			'.$gmini_cart_card.'

		</table>
	</div>
';
$messenger = '
<script>
	$(function(){
		get_messages();
		setInterval(get_messages, 2000);
	});
</script>

		<div id = "close_chat">X</div>
		<div class = "open_chat">Задать вопрос консультанту</div>
		<div id = "messenger">
			<div id = "status_consultant_chat" class = "top_chat_button">ON-LINE</div>
			<div id = "auth_chat_button" class = "top_chat_button">войти</div>
			<div class = "clear"></div>
			<div id = "chat">
				<div readonly id = "area_chat"></div>
				<textarea id = "input_chat">введите сообщение...</textarea>
				<div id = "send_chat">Отправить</div>
				
			</div>
		</div>
';

if(isset($_GET['logout'])){
	session_destroy();
	header("Location: http://danillg.doit1.ru/");
}
	
include($_SERVER['DOCUMENT_ROOT'].'/templates/header.tpl');

ob_end_flush();
?>