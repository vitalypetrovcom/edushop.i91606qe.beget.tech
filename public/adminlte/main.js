
$(function () { /* Функция для удаления категории товара с подтверждением удаления */

    $('.delete').click(function () { // При клике на иконку с классом '.delete', мы будем выполнять функцию
        let res = confirm('Подтвердите действие!'); // Возьмем в переменную res результат и запросим подтверждение confirm
        if (!res) { // Если пользователь не подтвердит удаление (!res == true)
            return false; // Вернем false
        }
    });

    $(".is-download").select2({ // Обращаемся к классу .is-download, для него вызываем метод select2
        placeholder: "Начните вводить наименование файла", // Передаем различные настройки
        minimumInputLength: 1,
        cache: true,
        ajax: { // Сам ajax запрос
            url: ADMIN + "/product/get-download", // Куда мы отправляем данные - на admin ProductController метод getDownloadAction
            delay: 250, // Задержка
            dataType: 'json', // Тип данных
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                return {
                    results: data.items,
                };
            },
        },
    });

    $('#is_download').on('select2:open', function () { /* Добавление автофокуса для курсора в поле формы для заполнения */
        document.querySelector('.select2-search__field').focus();
    });

    $('#is_download').on('select2:select', function () { // Добавление кнопки "Обычный товар", которая отменяет прикрепленный цифровой товар (файл), выбранный на предыдущих шагах - Превращает тип товара цифровой в обычный
        $('.clear-download').remove();
        $('#is_download').before('<p class="clear-download"><span class="btn btn-danger">Обычный товар</span></p>');
    });

    $('body').on('click', '.clear-download span', function () { // Удаляем динамично появляющуюся кнопку "Обычный товар" при клике на кнопку и описание цифрового товара
        $('#is_download').val(null).trigger('change');
        $('.clear-download').remove();
    });

    $('.card-body').on('click', '.del-img', function () { // По клику на класс '.card-body' в форме для добавления фото товара делегируем событие класса '.del-img' (красная кнопка для удаления загруженной картинки)
        const parentDiv = $(this).closest('.product-img-upload').remove(); // Удаляем все содержимое, что до класса '.product-img-upload'
        return false; // Возвращаем false чтобы у нас не срабатывала отправка формы при нажатии на красную кнопку удаления, тк это кнопка с действием
    });

    bsCustomFileInput.init();


});
