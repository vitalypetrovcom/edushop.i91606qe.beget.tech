<?php

namespace app\controllers;

use app\models\Wishlist;
use wfm\App;

/** @@property Wishlist $model */

class WishlistController extends AppController { // Контроллер (класс) для обработки запросов добавления товаров в избранные

    public function indexAction () { // Метод для отображения товаров в списке избранного. Кладем в куки только id-шники товаров, но показывать мы должны товары в зависимости от выбранного языка

        $lang = App::$app->getProperty ('language');  // Получим активный язык сайта
        $products = $this->model->get_wishlist_products ($lang) ; // Вызовем метод get_wishlist_products модели Wishlist (массив с данными о id товаров в списке избранного)
        /*debug ($products, 1); // Проверка правильности выполнения*/
        $this->setMeta (___ ('wishlist_index_title')); // Передадим мета-данные в вид
        $this->set (compact ('products'));  // Передаем сами данные в вид
    }

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

    public function deleteAction () { // Метод для удаления товаров из избранного

        $id = get('id');  // Получаем id товара из массива $_GET

        if ($this->model->delete_from_wishlist ($id)) { // Проверим, что у нас вернет метод delete_from_wishlist модели Wishlist
            $answer = ['result' => 'success', 'text' => ___ ('tpl_wishlist_delete_success')]; // Вернем сообщение об успешном удалении из избранного
        } else { // Иначе,
            $answer = ['result' => 'error', 'text' => ___ ('tpl_wishlist_delete_error')]; // Вернем сообщение об ошибке
        }
        exit(json_encode ($answer)); // Вернем ответ
    }

}