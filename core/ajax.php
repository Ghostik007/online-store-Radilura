<?

//подключение к БД
require_once($_SERVER["DOCUMENT_ROOT"]."/core/connect.php");
session_start();
//подключаем библиотеку пользовательских функций
require_once($_SERVER["DOCUMENT_ROOT"]."/core/lib/functions/_functions.php");
$response = array();

/*Подключаем куки*/
if(isset( $_SESSION[ 'uid' ] ) ){
	$uid = $_SESSION[ 'uid' ];
}else{
	$uid = time().rand( 1000, 99999 );
	$_SESSION[ 'uid' ] = $uid;
	/*setcookie( 'uid' , $uid , time() + 60*60*24*5 );*/
}

$mini_cart = false;
$gmini_cart = false;
$page_cart = false;
$data = '';


/* Проверяем наличие ПОСТ через AJAX*/
if( isset( $_POST[ 'add_cart' ] ) and $_POST[ 'add_cart' ] > 0 ){
	$element_id = intval( $_POST[ 'add_cart' ] );
	$query = mysql_query( "SELECT * FROM catalog_items WHERE id = '".$element_id."'" );
	if( mysql_num_rows($query) >= 1 ){		
		$query = mysql_query( "SELECT * FROM cart WHERE good_id = '".$element_id."' AND uid = '".$uid."' " );
		if( mysql_num_rows($query) >= 1 ){
			mysql_query( "UPDATE cart SET
				quantity = quantity+1
			WHERE
				uid = '".$uid."'
			AND
				good_id = '".$element_id."'
			");
		}else{
			mysql_query("INSERT INTO cart SET
				uid = '".$uid."',
				good_id = '".$element_id."'
			");
		}
		
		$mini_cart = true;
		$gmini_cart = true;
	}
}
//**********************
if(isset($_POST['quantity']) and $_POST['quantity'] > 0 and $_POST['value'] > 0){
	$id = $_POST['quantity'];
	$value = $_POST['value'];
	
	mysql_query("UPDATE	cart SET
		quantity = $value
	WHERE
		id = '$id'
	", $connect);	
	
	$response['quantity'] = $id;
	
	$mini_cart = true;
	$page_cart = true;
	
}

if(isset($_POST['del'])){
	$element_id = intval($_POST['del']);	
	mysql_query("
		DELETE 
		FROM 	cart 
		WHERE 	uid = '".$uid."'
		AND 	good_id = '".$element_id."'"
	);
	$mini_cart = true;
	$page_cart = true;
}


if( $mini_cart ){
	//формируем вывод мини корзины
	$query = mysql_query( "
	SELECT  	
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
	$gmini_cart = '<table class = "table table-striped" cellspacing = "0">
				<tr>
					<td><span class = "good_descript">Описание</span></td><td><span class = "good_descript">шт.</span></td><td><span class = "good_descript">Сумма</span></td>
				</tr>';
	
	if(mysql_num_rows($query) > 0){
		while($row = mysql_fetch_assoc($query)){
			$quantity += $row['quantity'];
			$sum += $row['quantity']*$row['price'];
			$goods_sum = $row['quantity']*$row['price'];
			$gmini_cart .= '
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
	$gmini_cart .= '</table>
	</div>';
	$response['mini_cart'] = array(
		'quantity' => $quantity,
		'sum' => $sum,
		'gmini_cart' => $gmini_cart
	);
}

if($page_cart){
		//формируем вывод корзины
	
	$query = mysql_query( "
	
	SELECT		c.id AS id,
				c.quantity AS quantity,
				ci.id AS good_id,
				ci.name AS name,
				ci.price AS price
	FROM 		cart AS c
	LEFT JOIN	catalog_items AS ci
	ON			c.good_id = ci.id
	WHERE 		uid = '$uid'
	
	");
	
	if(mysql_num_rows($query) > 0){
		$count = 0;
		$sum = 0;
		$quantity = 0;
		$response['page_cart'] = array(
			'quantity' => $quantity,
			'sum' => $sum,
			'table' => '');
		$response['page_cart']['table'] = '
						<table class = "table table-striped" cellspacing = "0">
						<thead style = "font-weight: bold;">
							<td>№</td> <td>Название</td> <td>Количество</td> <td>Цена</td> <td>Сумма</td> 
						</thead>
						<tbody>
					';
		mysql_data_seek($query, 0);
		while($row = mysql_fetch_assoc($query)){
			$count++;
			$id = $row['id'];
			$good_id = $row['good_id'];
			$good_name = stripslashes($row['name']);
			$price = $row['price'];
			$good_quantity  = $row['quantity'];
			
			$good_sum = $good_quantity*$price;
			$quantity += $good_quantity;
			$sum += $good_sum;
			
			$response['page_cart']['table'] .= '
				<tr>
					
						<td>'.$count.'</td>   <td><a href="/card/'.$good_id.'">'.$good_name.'</a></td>
						
						<td><input class="quantity input_table_cart" data-id="'.$id.'" type="number" value="'.$good_quantity.'" min="1" step="1"></td>
						
						<td>'.$price.'</td>		<td>'.number_format($good_sum, 2, ',', ' ').'</td>
						
						<td><div class="del" data-id="'.$good_id.'" title="Убрать позицию">X</div></td>
						
						
					</tr>
			';
			
		}
		
		$response['page_cart']['quantity'] = $quantity;
		$response['page_cart']['sum'] = number_format($sum, 2, ',', ' ');
		$response['page_cart']['table'] .= '
					</tbody>
					<tfoot>		<tr>
									<td colspan="2">ИТОГО:</td>		<td>'.$quantity.'</td>	<td colspan="3">'.number_format($sum, 2, ',', ' ').'</td>
					</tfoot>	</tr>
				</table>
			</div>
		';
		
	}
}


//echo $data;
echo json_encode($response);

?>