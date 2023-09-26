<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Product;
use wfm\App;

/** @property Product $model  */ // Подключаем модель для удобства работы с ProductController (редактор будет делать нам подсказки)

class ProductController extends AppController { // Контроллер (класс) для обработки товаров

    public function viewAction () { // Метод для отображения страницы товара
        $lang = App::$app->getProperty ('language');  // Получение языка в переменную $lang
        $product = $this->model->get_product ($this->route['slug'], $lang); // Получение данных о продукте в переменную $product
        /*debug ($product,1); // Проверка правильности выполнения запроса*/

        if (!$product) { // Проверка, достали ли мы нужный продукт (если, например, пользователь пришел к нам на сайт по битой ссылке)
            throw new \Exception("Товар по запросу {$this->route['slug']} не найден!", 404);  // Если нет, показываем сообщение об ошибке с кодом 404 (если отключен DEBUG)
        }
        /*debug ($product); // Проверка правильности выполнения запроса*/

        $breadcrumbs = Breadcrumbs::getBreadcrumbs ($product['category_id'], $product['title']);  // Получим хлебные крошки используя модель Breadcrumbs и метод getBreadcrumbs. На вход передаем id категории продукта "$product['category_id']" и текущее название продукта $product['title'], которое мы допишем в конец хлебных крошек
        /*debug ($breadcrumbs); // Выводим хлебные крошки для проверки*/


        $gallery = $this->model->get_gallery ($product['id']);  // Получаем все изображения из галереи для конкретного товара
        /*debug ($gallery); // Проверка правильности выполнения запроса*/
        $this->setMeta ($product['title'], $product['description'], $product['keywords']);  // Передадим все недостающие данные
        $this->set (compact ('product', 'gallery', 'breadcrumbs'));  // Передаем сами данные хлебных крошек используя метод "set"


    }


}