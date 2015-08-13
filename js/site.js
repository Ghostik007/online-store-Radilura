hcurpage = '';
$pid = '';
endpage = '';


$(function(){	
	
	$('#open_cart').hide();
	$('#gmini_cart').hide();
	$('.popup').hide();
	
	//добавляем товар в корзину
	$(document).on('click','.add_cart', function(){
		var id = $(this).attr("data-id");
		$.post("/core/ajax.php", { add_cart: id }, function(data, status){
			if(status == "success" && data !== ''){
				$("#mini_cart span:first").text(data.mini_cart.quantity+' шт.');
				$("#mini_cart span:last").text(data.mini_cart.sum+' руб.');
				$('#open_cart').show();
				$("#gmini_cart").html(data.mini_cart.gmini_cart);
				$('.popup').text('Товар добавлен').fadeIn().fadeOut(1000);
				//alert("Товар успешно добавлен в корзину.");
			}else{
				alert("Ошибка!");
			}			
		},'json');
	});
	
	$(document).on('change','.quantity', function(){
	//изменение количества товара в корзине
		var id = $(this).attr("data-id");
		var value =  $(this).val();
		$.post("/core/ajax.php", { quantity: id, value: value }, function(data, status){
			//console.log(data);
			if(status == "success" && data != undefined){
				$("#mini_cart span:first").text(data.mini_cart.quantity+' шт.');
				$("#mini_cart span:last").text(data.mini_cart.sum+' руб.');
				$("#gmini_cart").html(data.mini_cart.gmini_cart);
				$('.popup').text('Количество товара изменено').fadeIn().fadeOut(1000);
				if(data.page_cart == undefined){
					$("#out_cart").text('Ваша корзина пуста');
				}else{
					$('#out_cart').html(data.page_cart.table);
				}
			}else{
				alert("Ошибка!");
			}
		},'json');
	});
	
	//удаление товарной позиции корзине
		$(document).on('click','.del', function(){
		var id = $(this).attr("data-id");
		$.post("/core/ajax.php", { del: id }, function(data, status){
			if(status == "success" && data !== ''){
			$("#mini_cart span:first").text(data.mini_cart.quantity+' шт.');
				$("#mini_cart span:last").text(data.mini_cart.sum+' руб.');
				$("#gmini_cart").html(data.mini_cart.gmini_cart);
				$('.popup').text('Позиция удалена').fadeIn().fadeOut(1000);
				if(data.page_cart == undefined){
					$("#out_cart").text('Ваша корзина пуста');
				}else{
					$('#out_cart').html(data.page_cart.table);
				}

			}else{
				alert("Ошибка!");
			}			
		},'json');
	});	
	
	$(document).on('click','.del_position_gmini_cart', function(){
		var id = $(this).attr("data-id");
		$.post("/core/ajax.php", { del: id }, function(data, status){
			if(status == "success" && data !== ''){
				if(data.mini_cart.sum == '0,00'){
					$("#mini_cart span:first").text('0 шт.');
					$("#mini_cart span:last").text('0,00 руб.');
					$("#gmini_cart").hide();
					$("#open_cart").hide();
					$('.popup').text('Корзина пуста').fadeIn().fadeOut(1000);
					
				}else{
					$("#mini_cart span:first").text(data.mini_cart.quantity+' шт.');
					$("#mini_cart span:last").text(data.mini_cart.sum+' руб.');
					$("#gmini_cart").html(data.mini_cart.gmini_cart);
					$('#out_cart').html(data.page_cart.table);
					$('.popup').text('Позиция удалена').fadeIn().fadeOut(1000);
				}
			}else{
				alert("Ошибка!");
			}			
		},'json');
	});	
	
	//авторизация на сайте
	$(".button_auth").on("click", function(){
		var $auth_login = $("#auth_login").val();
		var $auth_password = $("#auth_password").val();
		if($auth_login !== '' && $auth_login !== 'логин'){
			if($auth_password !== '' && $auth_password !== '******'){
				$.post("/core/ajax_auth.php", { auth_login: $auth_login, auth_password: $auth_password }, function(data, status){
					if(status == "success" && data == 'ok'){
						location = "/";
						//setTimeout('window.location.reload()', 500);
					}else{
						alert("Неверный логин или пароль.");
					}			
				});			
			}else{
				alert("Вы ввели некорректный пароль");
			}			
		}else{
			alert("Вы ввели некорректный логин");
		}
	});
	$("#auth_password").on("keyup", function(event){
		if(event.keyCode == 13){
			var $auth_login = $("#auth_login").val();
			var $auth_password = $("#auth_password").val();
			if($auth_login !== '' && $auth_login !== 'логин'){
				if($auth_password !== '' && $auth_password !== '******'){
					$.post("/core/ajax_auth.php", { auth_login: $auth_login, auth_password: $auth_password }, function(data, status){
						if(status == "success" && data == 'ok'){
							location = "/";
						}else{
							alert("Неверный логин или пароль.");
						}			
					});			
				}else{
					alert("Вы ввели некорректный пароль");
				}			
			}else{
				alert("Вы ввели некорректный логин");
			}
		}
	});	
	
	$(document).on('click','#change_data',function(){
		if($("#c_pass").val() == '' && $("#c_pass2").val() == ''){
			$.post("/core/ajax_change_data.php", {
									make_change: 1,
									c_id: $("#c_id").val(),
									c_name: $("#c_name").val(),
									c_phone: $("#c_phone").val(),
									c_email: $("#c_email").val(),
									c_login: $("#c_login").val(),
									c_address: $("#c_address").val(),
				}, function(data,status){
				if(status == "success" && data == '1'){
					$(".warn").html("Данные успешно внесены");
					$("#c_email").css('color' , 'black');
				}else if(status == "success" && data == '0'){
					$(".warn").html("email уже занят");
					$("#c_email").css('color' , 'red');
				}
			})
		}
		if(!($("#c_pass").val() == '' && $("#c_pass2").val() == '') && $("#c_pass").val() == $("#c_pass2").val()){
			$.post("/core/ajax_change_data.php", {
									make_change: 2,
									c_id: $("#c_id").val(),
									c_name: $("#c_name").val(),
									c_phone: $("#c_phone").val(),
									c_email: $("#c_email").val(),
									c_login: $("#c_login").val(),
									c_pass: $("#c_pass").val(),
									c_address: $("#c_address").val(),
				}, function(data,status){
				if(status == "success" && data == '1'){
					$(".warn").html("Данные успешно внесены");
					$("#c_email").css('color' , 'black');
				}else if(status == "success" && data == '0'){
					$(".warn").html("email уже занят");
					$("#c_email").css('color' , 'red');
				}
			})
		}else if($("#c_pass").val() != $("#c_pass2").val()){
			$(".warn").html("Пароли не совпадают");
			$(".warn").show();
		}
	})

	$(document).on('click','.search_button',function(){
		var string = $(".search").val();
		//console.log(string);
		if(string != '' && string != 'поиск...'){
			location = "/search/?search="+string;
		}
	})
	
	$('#messenger').hide();
	$('#close_chat').hide();
	$(document).on('click','.open_chat',function(){
		$('#messenger').show();
		$('#input_chat').focus();
		$('#close_chat').show();
		$('.open_chat').hide();
	})
	$(document).on('click','#close_chat',function(){
		$('.open_chat').show();
		$('#messenger').hide();
		$('#close_chat').hide();
	})
	$(document).on('click','#auth_chat_button',function(){
		$('.open_chat').show();
		$('#messenger').hide();
		$('#close_chat').hide();
		$('#auth_login').focus();
		window.scroll(0 ,0);
	})
	
	$(document).on('focus','#input_chat',function(){
		if($(this).val() == 'введите сообщение...'){
				$(this).val('');
		}
	})
	
	$(document).on('blur','#input_chat',function(){
		if($(this).val() == ''){
				$(this).val('введите сообщение...');
		}
	})
	
	$(document).on('click','#send_chat',function(){
		if($('#input_chat').val() != '' && $('#input_chat').val() != 'введите сообщение...'){
			$.post("/core/ajax_messenger.php", {message: $('#input_chat').val()}, function(data,status){
				if(status == "success" && data != ''){
					$('#input_chat').val('');
				}else if(status == "success" && data == '0'){
					
				}
			})
		}
	})
	
	$("#input_chat").on("keypress", function(event){
		if(event.keyCode == 13){
			if($('#input_chat').val() != '' && $('#input_chat').val() != 'введите сообщение...'){
				$.post("/core/ajax_messenger.php", {message: $('#input_chat').val()}, function(data,status){
					if(status == "success" && data != ''){
						$('#input_chat').val('');
					}else if(status == "success" && data == '0'){
						
					}
				})
			}
		}
	})
	$(document).on('click','.response_to',function(){
		$.post("/core/ajax_response_to_user.php", {response_to: $(this).text()}, function(data,status){
			if(status == "success" && data != ''){
				$('#input_chat').val("Отвeт "+data+" : \n");
			}
		})
		$('#input_chat').focus();
	})
	
	
	
	$(document).on('change keyup input click','.input_price',function(){
		if (this.value.match(/[^0-9]/g)){
			this.value = this.value.replace(/[^0-9]/g, '');
		}
	})
	
	$(".subsection_menu").hide();
	//.slideToggle()
	$(document).on('mouseenter','.section_menu_container',function(){
		$(this).children('.subsection_menu').show(300);
	})
	
	$(document).on('mouseleave','.section_menu_container',function(){
		$(this).children('.subsection_menu').hide();
	})
	


//***************************
//блок каталога и навигации
//***************************

	hcurpage = '1';
	$pid = $('#pid').val();
	recordoffset = '';
	endpage = $('#endpage').val();
	

	
	$(document).on('click','#button_filter',function(){
		
		$.post("/core/ajax_catalog_and_nav.php", {
						pid: $pid, 
						get_goods_filter: 1 , 
						sort_by: $('#sort_by').val(), 
						sort_type: $('#sort_type').val(), 
						from_price: $('#from_price').val(), 
						between_price: $('#between_price').val(), 
						hcurpage: hcurpage}, 
		function(data,status){ 
			if(status == "success" && data != ''){
				$('#all_goods_list').html(data);
			}
		})
	})
	
	$(document).on('click','#startpage',function(){
		$.post("/core/ajax_catalog_and_nav.php", {
						pid: $pid, 
						sort_by: $('#sort_by').val(), 
						sort_type: $('#sort_type').val(), 
						from_price: $('#from_price').val(), 
						between_price: $('#between_price').val(), 
						curpage: 1},
		function(data,status){
			if(status == "success" && data != ''){
				$('#all_goods_list').html(data);
				hcurpage = '1';
			}
		})
	})
	
	$(document).on('click','#endpage',function(){
		$.post("/core/ajax_catalog_and_nav.php", {
						pid: $pid, 
						sort_by: $('#sort_by').val(), 
						sort_type: $('#sort_type').val(), 
						from_price: $('#from_price').val(), 
						between_price: $('#between_price').val(), 
						curpage: endpage}, 
		function(data,status){
			if(status == "success" && data != ''){
				$('#all_goods_list').html(data);
				hcurpage = endpage;
			}
		})
	})

	$(document).on('click','#page1right',function(){
		$.post("/core/ajax_catalog_and_nav.php", {
						pid: $pid, 
						sort_by: $('#sort_by').val(), 
						sort_type: $('#sort_type').val(), 
						from_price: $('#from_price').val(), 
						between_price: $('#between_price').val(), 
						curpage: $('#page1right').text()}, 
		function(data,status){
			if(status == "success" && data != ''){
				$('#all_goods_list').html(data);
				hcurpage = $('#page1right').text();
			}
		})
	})
	
	$(document).on('click','#page1left',function(){
		$.post("/core/ajax_catalog_and_nav.php", {
						pid: $pid, 
						sort_by: $('#sort_by').val(), 
						sort_type: $('#sort_type').val(), 
						from_price: $('#from_price').val(), 
						between_price: $('#between_price').val(), 
						curpage: $('#page1left').text()}, 
		function(data,status){
			if(status == "success" && data != ''){
				$('#all_goods_list').html(data);
				hcurpage = $('#page1left').text();
			}
		})
	})
	
	$(document).on('click','#page2right',function(){
		$.post("/core/ajax_catalog_and_nav.php", {
						pid: $pid, 
						sort_by: $('#sort_by').val(), 
						sort_type: $('#sort_type').val(), 
						from_price: $('#from_price').val(), 
						between_price: $('#between_price').val(), 
						curpage: $('#page2right').text()}, 
		function(data,status){
			if(status == "success" && data != ''){
				$('#all_goods_list').html(data);
				hcurpage = $('#page2right').text();
			}
		})
	})
	
	$(document).on('click','#page2left',function(){
		$.post("/core/ajax_catalog_and_nav.php", {
						pid: $pid, 
						sort_by: $('#sort_by').val(), 
						sort_type: $('#sort_type').val(), 
						from_price: $('#from_price').val(), 
						between_price: $('#between_price').val(), 
						curpage: $('#page2left').text()}, 
		function(data,status){
			if(status == "success" && data != ''){
				$('#all_goods_list').html(data);
				hcurpage = $('#page2left').text();
			}
		})
	})
	
	$(document).on('click','#page3right',function(){
		$.post("/core/ajax_catalog_and_nav.php", {
						pid: $pid, 
						sort_by: $('#sort_by').val(), 
						sort_type: $('#sort_type').val(), 
						from_price: $('#from_price').val(), 
						between_price: $('#between_price').val(), 
						curpage: $('#page3right').text()}, 
		function(data,status){
			if(status == "success" && data != ''){
				$('#all_goods_list').html(data);
				hcurpage = $('#page3right').text();
			}
		})
	})
	
	$(document).on('click','#page3left',function(){
		$.post("/core/ajax_catalog_and_nav.php", {
						pid: $pid, 
						sort_by: $('#sort_by').val(), 
						sort_type: $('#sort_type').val(), 
						from_price: $('#from_price').val(), 
						between_price: $('#between_price').val(), 
						curpage: $('#page3left').text()}, 
		function(data,status){
			if(status == "success" && data != ''){
				$('#all_goods_list').html(data);
				hcurpage = $('#page3left').text();
			}
		})
	})
	
	var open_flag = 0;
	$(document).on('click','#open_cart',function(){
		if(open_flag == 0){
			$('#gmini_cart').show();
			$('#open_cart').html('-');
			open_flag = 1;
		}else{
			$('#gmini_cart').hide();
			$('#open_cart').html('+');
			open_flag = 0;
		}
	})
	
});
