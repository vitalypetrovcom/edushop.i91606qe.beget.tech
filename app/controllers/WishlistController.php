<?php

namespace app\controllers;

use app\models\Wishlist;

/** @@property  $model */

class WishlistController extends AppController { // Контроллер (класс) для обработки запросов добавления товаров в избранные

    public function addAction () { // Метод добавления товаров в избранные. Задача данного метода вернуть 'id' если он есть. Если его нет, мы будем знать, что такого товара у нас нет - пользователь прислал нам неправильный 'id' запрос

        $id = get('id'); // Получаем 'id' товара для добавления
        if (!$id) { // Проверка: если мы не получили $id
            $answer = ['result' => 'error', 'text' => ___ ('tpl_wishlist_add_error')]; // Формируем ответ: ответ мы ожидаем в формате JSON, поэтому нам нужен массив и функция JSON_encode, которая его корректно преобразует в нужный нам формат ответа
            exit(json_encode ($answer)); // Преобразуем ответ $answer с помощью функции json_encode и завершаем выполнение кода (дальнейший код не будет выполнен)
        }

        $product = $this->model->get_product ($id); // Получаем данные о товаре используя модель Wishlist и метод get_product по $id
        if ($product) { // Проверяем, если мы получили товар

            $this->model->add_to_wishlist ($id); // Вызываем модель Wishlist и метод add_to_wishlist

            $answer = ['result' => 'success', 'text' => ___ ('tpl_wishlist_add_success')]; // Вернем сообщение об успешном добавлении в избранное
        } else { // Иначе,
            $answer = ['result' => 'error', 'text' => ___ ('tpl_wishlist_add_error')]; // Вернем сообщение об ошибке
        }
        exit(json_encode ($answer)); // Вернем ответ
    }

}