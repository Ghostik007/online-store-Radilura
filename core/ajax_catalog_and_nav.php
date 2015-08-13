<?
//подключение к БД
require_once($_SERVER["DOCUMENT_ROOT"]."/core/connect.php");
//подключаем библиотеку пользовательских функций
require_once($_SERVER["DOCUMENT_ROOT"]."/core/lib/functions/_functions.php");

function pages_list($t,$c){
	$out = '';
	$p = 12;
	$cp = '';
	$r = '';
	$n = ceil($t/$p);
	if($c != ''){
		if ($c < 1) {
			$cp = 1;
		}elseif ($c > $n) {
			$cp = $n;
		}
		$cp = $c;
		$r = ($cp - 1) * $p;
	}else {
		$cp = 1;
		$r = 0;
	}
	$out = array($cp,$r,$n,$p);
	return $out;
}

if(isset($_POST['get_goods_filter'])){
	$out = '';
	$sort_by = 'ASC';
	$sort_type = 'pos';
	if($_POST['sort_by'] == '2'){
		$sort_by = 'DESC';
	}
	if($_POST['sort_type'] == '2'){
		$sort_type = 'price';
	}
		if($_POST['from_price'] == '' and $_POST['between_price'] == ''){

			$totalrecords = mysql_num_rows(mysql_query("SELECT id FROM catalog_items WHERE pid='".$_POST['pid']."' ORDER BY ".$sort_type." ".$sort_by));
			$arr = pages_list($totalrecords,$_POST['hcurpage']);
			$cur_page = $arr[0];
			$recordoffset = $arr[1];
			$numpages = $arr[2];
			$perpage = $arr[3];
			$navigate = navigate($cur_page, $numpages);
			$out .= $navigate;
			$query = mysql_query("
						SELECT *
						FROM catalog_items
						WHERE pid = '".$_POST['pid']."'
						ORDER BY ".$sort_type." ".$sort_by." 
						LIMIT $recordoffset, $perpage
					");
		}else{
			$from_price = '0';
			$between_price = '1000000';
			if($_POST['from_price'] != ''){
				$from_price = $_POST['from_price'];
			}
			if($_POST['between_price'] != ''){
				$between_price = $_POST['between_price'];
			}

			$totalrecords = mysql_num_rows(mysql_query("SELECT id FROM catalog_items WHERE pid='".$_POST['pid']."' AND price >= '".$from_price."' AND price <= '".$between_price."' ORDER BY ".$sort_type." ".$sort_by));
			$arr = pages_list($totalrecords,$_POST['curpage']);
			$cur_page = $arr[0];
			$recordoffset = $arr[1];
			$numpages = $arr[2];
			$perpage = $arr[3];
			$navigate = navigate($cur_page, $numpages);
			$out .= $navigate;

			$query = mysql_query("
						SELECT *
						FROM catalog_items
						WHERE pid = '".$_POST['pid']."'
						AND price >= '".$from_price."'
						AND price <= '".$between_price."'
						ORDER BY ".$sort_type." ".$sort_by." 
						LIMIT $recordoffset, $perpage
					");
		}
}

if(isset($_POST['curpage'])){
	$out = '';
	$sort_by = 'ASC';
	$sort_type = 'pos';
	if($_POST['sort_by'] == '2'){
		$sort_by = 'DESC';
	}
	if($_POST['sort_type'] == '2'){
		$sort_type = 'price';
	}
		if($_POST['from_price'] == '' and $_POST['between_price'] == ''){

			$totalrecords = mysql_num_rows(mysql_query("SELECT id FROM catalog_items WHERE pid='".$_POST['pid']."' ORDER BY ".$sort_type." ".$sort_by));
			$arr = pages_list($totalrecords,$_POST['curpage']);
			$cur_page = $arr[0];
			$recordoffset = $arr[1];
			$numpages = $arr[2];
			$perpage = $arr[3];
			$navigate = navigate($cur_page, $numpages);
			$out .= $navigate;

			$query = mysql_query("
						SELECT *
						FROM catalog_items
						WHERE pid = '".$_POST['pid']."'
						ORDER BY ".$sort_type." ".$sort_by." 
						LIMIT $recordoffset, $perpage
					");
		}else{
			$from_price = '0';
			$between_price = '1000000';
			if($_POST['from_price'] != ''){
				$from_price = $_POST['from_price'];
			}
			if($_POST['between_price'] != ''){
				$between_price = $_POST['between_price'];
			}

			$totalrecords = mysql_num_rows(mysql_query("SELECT id FROM catalog_items WHERE pid='".$_POST['pid']."' AND price >= '".$from_price."' AND price <= '".$between_price."' ORDER BY ".$sort_type." ".$sort_by));
			$arr = pages_list($totalrecords,$_POST['curpage']);
			$cur_page = $arr[0];
			$recordoffset = $arr[1];
			$numpages = $arr[2];
			$perpage = $arr[3];
			$navigate = navigate($cur_page, $numpages);
			$out .= $navigate;

			$query = mysql_query("
						SELECT *
						FROM catalog_items
						WHERE pid = '".$_POST['pid']."'
						AND price >= '".$from_price."'
						AND price <= '".$between_price."'
						ORDER BY ".$sort_type." ".$sort_by." 
						LIMIT $recordoffset, $perpage
					");
		}
}

	
	if(mysql_fetch_assoc($query) != ''){
		mysql_data_seek($query, 0);
		while ($row = mysql_fetch_assoc($query)){
			if (is_file($_SERVER['DOCUMENT_ROOT'].'/img/'.$row['photo'])){
				$img = '<img src="/img/'.$row['photo'].'" width="150" border="0">';
			}else{
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
	}
	echo $out;

?>