<!DOCTYPE>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title><?=$title;?></title>
		<meta name="keywords" content="<?=$keywords;?>">
		<meta name="description" content="<?=$description;?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/site.js"></script>
		<script src="/js/bootstrap.js"></script>
		<link type="text/css" href="/css/style.css" rel="stylesheet">
		<link type="text/css" href="/css/bootstrap222.css" rel="stylesheet">

		
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

	</head>
	<body>
		<?=$popup?>
		<div id = "backing">
		<?=$messenger?>
		<?=$gmini_cart?>
		<div class="header">
			<a href="/" class="logo"></a>
			<div class="header_phones"><?=$phone;?></div>
			<div class="auth">
				<?=$auth?>
			</div>
			<div class="bg_img"></div>
			<div class="header_menu">
				<a href="/news/"><span style="background-image: url(/img/brands.png);"></span>Новости</a>
				<a href="/catalog/"><span style="background-image: url(/img/catalog.png);"></span>Каталог</a>
				<a href="/articles/"><span style="background-image: url(/img/search.png);"></span>Статьи</a>
				<a href="/delivery/"><span style="background-image: url(/img/delivery.png);"></span>Доставка</a>
				<a href="/payment/"><span style="background-image: url(/img/payment.png);"></span>Оплата</a>
				<a href="/contacts/"><span style="background-image: url(/img/contacts.png);"></span>Контакты</a>
				<a href="/cart/"><span style="background-image: url(/img/cart.png);"></span>Корзина</a>
			</div>
			<div class="header_menu_2">
				<input class="search" type="text" placeholder="поиск...">
				<img class="search_button" src="/img/arrow_dark_blue.png" border="0" title="найти">
				<div class="cart" id="mini_cart">
					<a href = "/cart/" style="text-decoration: none"><?=$mini_cart?></a>
				</div>
			</div>
		</div>