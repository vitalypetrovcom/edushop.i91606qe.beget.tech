<?php

namespace app\controllers;

use app\models\Cart;
use wfm\App;

/** @property Cart $model */ // Объявляем модель
class CartController extends AppController { // Контроллер для работы с корзиной

    public function addAction () {

        $lang = App::$app->getProperty ('language'); // Берем язык из контейнера "App::$app->getProperty" по ключу 'language'
        $id = get ('id'); // Получаем данные по ключу 'id' используя функцию "get"
        $qty = get ('qty'); //  - - -

        if (!$id) { // Проверка: если в $id будет что-то не являющееся числом, тогда дальнейшая работа бессмысленна - мы вернем false
            return false;
        }

        $product = $this->model->get_product ($id, $lang); // Вызываем метод в контроллере
        debug ($product, 1);  // Проверка, получили ли мы продукт (есть ли вообще такой продукт, который запросил пользователь)

    }

}