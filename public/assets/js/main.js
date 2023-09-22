$(function() {

	/* CART */ /*Здесь будут функции, которые относятся к работе корзины */

	$('.add-to-cart').on('click', function (e) { // К классу "add-to-cart" будем привязываться: отслеживаем события по клику 'click' и по этому событию будем выполнять функцию
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
				console.log(res);
			},
			error: function () { // В случае ошибки, мы выведем сообщение об ошибке
				alert('Error!');
			}
		})

	});

	/* CART */

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


});