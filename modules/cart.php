<?
if($query = mysql_query("SELECT * FROM content WHERE link='".$page."'") and mysql_fetch_assoc($query)!=''){
		mysql_data_seek($query, 0);
		$row = mysql_fetch_assoc($query);
		$name = 'Корзина товаров';
		$good_name = $row['name'];
		$content = $row['text'];
		
		$bread = breadcrumbs($row['id']);
		
		$out = '';
		
if( isset( $_POST['name'] , $_POST['phone'] , $_POST['email'] ) ){
	$fio = $_POST['name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$comment = $_POST['comment'];
	
	$goods = '';
	
	//запрашиваем товары в корзине
	$query = mysql_query("
	SELECT  	c.quantity AS quantity,
				ci.id AS good_id,
				ci.name AS name,
				ci.price AS price
	FROM 		cart AS c
	LEFT JOIN 	catalog_items AS ci
	ON 			c.good_id = ci.id
	WHERE 		uid = '$uid'
	", $connect);
	if(mysql_num_rows($query) > 0){		
		$all_sum = 0;
		$quantity = 0;
		$message = '
			<table class = "table table_sriped">
				<thead style = "font-weight: bold;">
					<td>№</td> <td>Название</td> <td>Количество</td> <td>Цена</td> <td>Сумма</td> 
				</thead>';
				
		$count = 0;
		while($row = mysql_fetch_assoc($query)){
			$count++;
			$good_id = $row['good_id'];
			$good_name = stripslashes($row['name']);
			$price = $row['price'];
			$good_quantity  = $row['quantity'];
			$good_sum = $good_quantity*$price;
			$quantity += $good_quantity;
			$all_sum += $good_sum;
			
			$goods .= $good_id.':'.$good_quantity.':'.$price.';';
			
			$message .= '
				<tr>
					<td>'.$count.'</td> <td>'.$good_name.'</td> <td>'.$good_quantity.'</td> <td>'.$price.'</td> <td>'.number_format($good_sum, 2, ',', ' ').'</td>
				</tr>
			';
		}
		
		$goods = substr($goods, 0, -1);
		
		$message .= '
			<tr>
				<td colspan="2">ИТОГО:</td>
				<td>'.$quantity.'</td>
				<td colspan="2">'.number_format($all_sum, 2, ',', ' ').'</td>
			</tr>
		</table>
		';
		
		mysql_query("INSERT INTO 
			orders
		SET
			goods = '$goods',
			name = '$fio',
			phone = '$phone',
			email = '$email',
			address = '$address',
			comment = '$comment'
		");
		
		$order_id = mysql_insert_id();
		if($order_id > 0){
			$date = date("d.m.Y");
			
			$subject = "=?utf-8?B?".base64_encode("Заказ с сайта ".$_SERVER['HTTP_HOST']."")."?=";	
			$headers = "From: ".$_SERVER['HTTP_HOST']." <".$_SERVER['HTTP_HOST'].">\r\nContent-type: text/html; charset=utf-8";  
			
			mail($email,
			$subject,
			"Здравствуйте, ".$fio."
			<br><br>
			Вами был сделан заказ товаров на сайте <a href=\"http://".$_SERVER['HTTP_HOST']."\" >".$_SERVER['HTTP_HOST']."</a>
			<br><br>
			Заказ  № ".$order_id." от ".$date."
			".$message."
			<br><br>
			Наши менеджеры постараются связаться с Вами как можно скорее. Спасибо за заказ!	
			<br><br>				
			Это письмо отправлено автоматической системой, отвечать на него не нужно.
			",
			$headers);
			
			mysql_query("DELETE FROM cart WHERE uid='$uid'", $connect);
			
			$out .= 'Заказ успешно отправлен.';
		}
	}       
}else{
	//запрашиваем товары в корзине
	$query = mysql_query("
	SELECT		c.id AS id,
				c.quantity AS quantity,
				ci.id AS good_id,
				ci.name AS name,
				ci.price AS price
	FROM 		cart AS c
	LEFT JOIN	catalog_items AS ci
	ON			c.good_id = ci.id
	WHERE 		uid = '$uid'
	", $connect);
	if(mysql_num_rows($query) > 0){
		$sum = 0;
		$quantity = 0;
		$out .= '
			<div id="out_cart" >
				<table class = "table table-striped" cellspacing = "0">
					<thead style = "font-weight: bold;">
						<td>№</td> <td>Название</td> <td>Количество</td> <td>Цена</td> <td>Сумма</td> 
					</thead>
					<tbody>
		';
		$count = 0;
		while($row = mysql_fetch_assoc($query)){
			$count++;
			$id = $row['id'];
			$good_id = $row['good_id'];
			$good_name = stripslashes($row['name']);
			$price = $row['price'];
			$good_quantity  = $row['quantity'];
			
			$good_sum = $good_quantity*$price;
			$quantity += $good_quantity;
			$all_sum += $good_sum;
			
			$out .= '
					<tr>
					
						<td>'.$count.'</td>   <td><a href="/card/'.$good_id.'">'.$good_name.'</a></td>
						
						<td><input class="quantity input_table_cart" data-id="'.$id.'" type="number" value="'.$good_quantity.'" min="1" step="1"></td>
						
						<td>'.$price.'</td>		<td>'.number_format($good_sum, 2, ',', ' ').'</td>
						
						<td><div class="del" data-id="'.$good_id.'" title="Убрать позицию">X</div></td>
						
						
					</tr>
			';
		}
		$out .= '
					</tbody>
					<tfoot>		<tr>
									<td colspan="2">ИТОГО:</td>		<td>'.$quantity.'</td>	<td colspan="3">'.number_format($all_sum, 2, ',', ' ').'</td>
					</tfoot>	</tr>
				</table>
			</div>
		';
		$out .= '
			<form id="order" action="" method="POST" autocomplete="on" enctype="multipart/form-data">
				<br>	*Имя		<br>	<input name="name" type="text" required value="">
				<br>	*D формате +79123456789		<br>	<input name="phone" type="text" required pattern="^\+[0-9]+$" value="">
				<br>	*E-mail		<br>	<input name="email" type="email" required value="">
				<br>	Адрес		<br>	<textarea name="address" style = "resize: none;"></textarea>
				<br>	Комментарий	<br>	<textarea name="comment" style = "resize: none;"></textarea>
				<br>	<input type="submit" value="Оформить заказ">
			</form>
		';
	}else{
		$out .= '
			Ваша корзина пуста.
		';
	}
}
	$content .= $out;
	//подключаем шаблон
	include($_SERVER['DOCUMENT_ROOT'].'templates/inner.tpl');
}



?>