$(function() {

	/* CART */ /*Здесь будут функции, которые относятся к работе корзины */

	function showCart(cart) { /* Метод, который будет показывать содержимое корзины */
		$('#cart-modal .modal-cart-content').html(cart); // Обращаемся к id #cart-modal и внутри ищем класс .modal-cart-content. Методом html вставляем нашу корзину cart
		const myModalEl = document.querySelector('#cart-modal'); // Подготавливаем наше модальное окно используя bootstrap методом getOrCreateInstance
		const modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
		modal.show(); // Выводим наше модальное окно

		if ($('.cart-qty').text()) { // Проверим, есть ли у нас элемент с классом ".cart-qty" и у него "text()" не пустой
			$('.count-items').text($('.cart-qty').text()); // Тогда, мы обратимся к элементу с классом '.count-items' и методом "text()" мы добавим в него "$('.cart-qty').text()"
		} else { // Иначе, если "'.cart-qty'" у нас не будет (Empty cart)
			$('.count-items').text('0') // Мы в "$('.count-items')" методом "text" вставим значение "0"
		}
	}

	$('#get-cart').on('click', function (e) { // Подстановка данных с сервера о товарах в корзине в модальное окно. Отслеживаем событие по "id=get-cart" по клику и выполним функцию: добавляем объект события "e", отменяем дефолтное поведение ссылки, отправляем Ajax запрос на сервер
		e.preventDefault(); // Отменяем дефолтное поведение ссылки (переход по ссылке)
		/* Отправка Ajax запроса */
		$.ajax({
			url: 'cart/show', // url, на который будет отправлен наш запрос
			type: 'GET',  // Метод передачи данных
			success: function (res) { // success - что мы будем делать в случае успеха, выполняем функцию, результат выполнения которой мы сохраним в переменную res
				/*console.log(res);*/ /* Вывод в консоль браузера */
				showCart(res); /* Вывод содержимого корзины в браузер */
			},
			error: function () { // В случае ошибки, мы выведем сообщение об ошибке
				alert('Error!');
			}
		})

	});

	$('#cart-modal .modal-cart-content').on('click', '.del-item', function (e) { // Функция для удаления товара из корзины в модельном окне. Передаем объект события "e". Нам необходимо делегирование событий, мы обращаемся к уже существующему родителю "'#cart-modal .modal-cart-content'" и для него отслеживаем события клика и делегируем событие клика для элемента с классом "'.del-item'". По этому событию будем выполнять функцию:
		e.preventDefault(); // Отменяем дефолтное поведение ссылки (переход по ссылке)
		const id = $(this).data('id');  // Получаем "id": обратимся к текущему элементу "(this)" и возьмем у него "data" аттрибут 'id'.

		/* Отправка Ajax запроса */
		$.ajax({
			url: 'cart/delete', // url, на который будет отправлен наш запрос
			type: 'GET',  // Метод передачи данных
			data: {id: id}, // Данные, которые мы будем отправлять (объект)
			success: function (res) { // success - что мы будем делать в случае успеха, выполняем функцию, результат выполнения которой мы сохраним в переменную res
				/*console.log(res);*/ /* Вывод в консоль браузера */

				const url = window.location.toString();  // Нам нужно понять, гда мы находимся. Для этого мы получим текущий url
				if (url.indexOf('cart/view') !== -1) { // Мы проверим, если у нас url.indexOf есть строка 'cart/view' (мы находимся на странице корзины с товарами), тогда мы будем делать
					window.location = url; // Тогда мы перезагрузим эту страницу
				} else {
					showCart(res); /* Вывод содержимого корзины в браузер */
				}
			},
			error: function () { // В случае ошибки, мы выведем сообщение об ошибке
				alert('Error!');
			}
		})

	});

	$('#cart-modal .modal-cart-content').on('click', '#clear-cart', function () { // Функция для удаления всех товаров из корзины (очищение корзины) в модельном окне. Мы обращаемся к уже существующему родителю "'#cart-modal .modal-cart-content'" и для него отслеживаем события клика и делегируем событие клика для элемента с id '#clear-cart'. По этому событию будем выполнять функцию:
		/* Отправка Ajax запроса */
		$.ajax({
			url: 'cart/clear', // url, на который будет отправлен наш запрос
			type: 'GET',  // Метод передачи данных
			success: function (res) { // success - что мы будем делать в случае успеха, выполняем функцию, результат выполнения которой мы сохраним в переменную res
				/*console.log(res);*/ /* Вывод в консоль браузера */
				showCart(res); /* Вывод содержимого корзины в браузер */
			},
			error: function () { // В случае ошибки, мы выведем сообщение об ошибке
				alert('Error!');
			}
		})

	});

	$('.add-to-cart').on('click', function (e) { // Добавление данных с сервера о товарах в корзине в модальное окно. К классу "add-to-cart" будем привязываться: отслеживаем события по клику 'click' и по этому событию будем выполнять функцию
		e.preventDefault(); // Отменяем дефолтное поведение ссылки (переход по ссылке)
		const id = $(this).data('id'); // Берем id товара
		const qty = $('#input-quantity').val() ? $('#input-quantity').val() : 1; // Количество товара (может быть возможность выбирать количество или нет (карточка продукта)). Если есть, берем его, если нет - по умолчанию 1 шт.
		const $this = $(this); // Текущий объект, по которому был клик
		/*console.log(id, qty); // Проверочный вывод в консоль переменных id, qty*/

		/* Отправка Ajax запроса */
		$.ajax({
			url: 'cart/add', // url, на который будет отправлен наш запрос
			type: 'GET',  // Метод передачи данных
			data: {id: id, qty: qty}, // Данные, которые мы будем отправлять (объект)
			success: function (res) { // success - что мы будем делать в случае успеха, выполняем функцию, результат выполнения которой мы сохраним в переменную res
				/*console.log(res);*/ /* Вывод в консоль браузера */
				showCart(res); /* Вывод содержимого корзины в браузер */
				$this.find('i').removeClass('fa-shopping-cart').addClass('fa-luggage-cart');
				// $this указывает на текущий объект события
			},
			error: function () { // В случае ошибки, мы выведем сообщение об ошибке
				alert('Error!');
			}
		})

	});

	/* CART */

	$('#input-sort').on('change', function () {  // Обработчик функции сортировки на странице категории товаров
		window.location = PATH + window.location.pathname + '?' + $(this).val()  // Мы берем то, что у нас есть в адресной строке прибавляем '?' и текущее значение $(this).val() (например, sort=title_desc)
	});

	$('.open-search').click(function(e) {
		e.preventDefault();
		$('#search').addClass('active');
	});
	$('.close-search').click(function() {
		$('#search').removeClass('active');
	});

	$(window).scroll(function() {
		if ($(this).scrollTop() > 200) {
			$('#top').fadeIn();
		} else {
			$('#top').fadeOut();
		}
	});

	$('#top').click(function() {
		$('body, html').animate({scrollTop:0}, 700);
	});

	$('.sidebar-toggler .btn').click(function() {
		$('.sidebar-toggle').slideToggle();
	});

	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled: true
		},
		removalDelay: 500,
		callbacks: {
			beforeOpen: function() {
				this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
				this.st.mainClass = this.st.el.attr('data-effect');
			}
		}
	});

	$('#languages button').on('click', function () {
		const lang_code = $(this).data('langcode');
		window.location = PATH + '/language/change?lang=' + lang_code;

	});

	$('.product-card').on('click', '.add-to-wishlist', function (e) { // Функция добавления товара в избранные. Нам понадобится объект события "e"
		e.preventDefault(); // Отменяем дефолтное действие (переход по ссылке)
		const id = $(this).data('id');  // Получим id товара для добавления в избранные
		const $this = $(this); // Текущая ссылка на товар

		/* Отправка Ajax запроса */
		$.ajax({
			url: 'wishlist/add',  // url на который будут уходить данные
			type: 'GET',  // Метод отправки данных 'GET'
			data: {id: id}, // Данные, которые будут отправляться: id
			success: function (res) { // Ответ мы будем получать в переменную res
				res = JSON.parse(res); // Ответ мы будем принимать в формате JSON, поэтому нужно распарсить его
				/*console.log(res); // Выведение в консоль*/

				Swal.fire( // <!-- Используем библиотеку sweetalert2.js - красивая, отзывчивая, настраиваемая, доступная (WAI-ARIA) замена для JavaScript's popup boxes -->
					res.text,
					'',
					res.result
				);
				if (res.result == 'success' ) {
					$this.removeClass('add-to-wishlist').addClass('delete-from-wishlist'); // Возможность удаления товара из избранного. Удалим класс для ссылки
					$this.find('i').removeClass('far fa-heart').addClass('fas fa-hand-holding-heart'); // Замена иконки добавления в избранные после добавления товара. Найдем иконку $this.find('i'), удалим у нее класс removeClass('far fa-heart') и добавим ей класс addClass('fas fa-hand-holding-heart')
				}
			},
			error: function () { // В случае ошибки:
				alert('Error!');
			}
		})

	});

	$('.product-card').on('click', '.delete-from-wishlist', function (e) { // Функция удаления товара из избранного. Нам понадобится объект события "e"
		e.preventDefault(); // Отменяем дефолтное действие (переход по ссылке)
		const id = $(this).data('id');  // Получим id товара для удаления из избранные
		const $this = $(this); // Текущая ссылка на товар

		/* Отправка Ajax запроса */
		$.ajax({
			url: 'wishlist/delete',  // url на который будут уходить данные
			type: 'GET',  // Метод отправки данных 'GET'
			data: {id: id}, // Данные, которые будут отправляться: id
			success: function (res) { // Ответ мы будем получать в переменную res
				const url = window.location.toString();  // Нам нужно понять, гда мы находимся. Для этого мы получим текущий url
				if (url.indexOf('wishlist') !== -1) { // Мы проверим, если у нас url.indexOf есть строка 'wishlist' (мы находимся на странице избранного), тогда мы будем делать
					window.location = url; // Тогда мы перезагрузим эту страницу
				} else { // Иначе,
					res = JSON.parse(res); // Получаем ответ. Ответ мы будем принимать в формате JSON, поэтому нужно распарсить его
					Swal.fire( // Выводим ответ красиво. <!-- Используем библиотеку sweetalert2.js - красивая, отзывчивая, настраиваемая, доступная (WAI-ARIA) замена для JavaScript's popup boxes -->
						res.text,
						'',
						res.result
					);
					if (res.result == 'success' ) {
						$this.removeClass('delete-from-wishlist').addClass('add-to-wishlist'); // Возможность добавления товара в избранное.
						$this.find('i').removeClass('fas fa-hand-holding-heart').addClass('far fa-heart'); // Замена иконки добавления в избранные после удаления товара. Найдем иконку $this.find('i'), удалим у нее класс removeClass('fas fa-hand-holding-heart') и добавим ей класс addClass('far fa-heart')
					}
				}
			},
			error: function () { // В случае ошибки:
				alert('Error!');
			}
		})

	});


});