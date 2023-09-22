<?php

namespace app\controllers;

use app\models\Cart;
use wfm\App;

/** @property Cart $model */ // Объявляем модель
class CartController extends AppController { // Контроллер для работы с корзиной

    public function addAction (): bool {

        $lang = App::$app->getProperty ('language'); // Берем язык из контейнера "App::$app->getProperty" по ключу 'language'
        $id = get ('id'); // Получаем данные по ключу 'id' используя функцию "get"
        $qty = get ('qty'); //  - - -

        if (!$id) { // Проверка: если в $id будет что-то не являющееся числом, тогда дальнейшая работа бессмысленна - мы вернем false
            return false;
        }

        $product = $this->model->get_product ($id, $lang); // Получаем данные о продукте. Вызываем метод в контроллере
//        debug ($product, 1);  // Проверка, получили ли мы продукт (есть ли вообще такой продукт, который запросил пользователь)
        if (!$product) { // Проверка: если в $product будет пустой массив - мы вернем false
            return false;
        }

        $this->model->add_to_cart ($product, $qty); // Обращаемся к модели model и добавляем товар в корзину

        if ($this->isAjax ()) { // Проверяем, отправлялся ли запрос методом Ajax или нет? тогда мы должны подключить какой-либо вид без шаблона, и у нас этот вид вернется в ответ на Ajax запрос
             debug ($_SESSION['cart'], 1);
        }
        redirect (); // Иначе, выполняем редирект и пользователь будет возвращен на ту же страницу, с которой он к нам пришел
        return true;

    }

}