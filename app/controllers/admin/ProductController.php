<?php

namespace app\controllers\admin;

use app\models\admin\Product;
use RedBeanPHP\R;
use wfm\App;
use wfm\Pagination;

/** @property Product $model*/

class ProductController extends AppController { // Контроллер (класс) для работы с товарами в админ-панели

    public function indexAction () { // Метод для отображения списка товаров в админ-панели

        $lang = App::$app->getProperty ('language'); // Получаем текущий активный язык сайта из контейнера App
        $page = get('page'); // Нам потребуется здесь пагинация, поэтому получим GET параметр page для пагинации
        $perpage = 3; // Количество товаров на странице
        $total = R::count ('product'); // Нам нужно понять сколько всего у нас товаров
        $pagination = new Pagination($page, $perpage, $total); // Получаем объект пагинации. Передаем на вход нужные параметры $page, $perpage, $total
        $start = $pagination->getStart (); // Переменная $start - с какой позициии нам начинать выбирать записи из БД при выдаче на страницу при пагинации

        $products = $this->model->get_products ($lang, $start, $perpage); // Получаем товары из БД в переменную $products. На вход передаем параметры $lang, $start, $perpage

        $title = 'Список товаров'; // Объявляем переменную $title
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title', 'products', 'pagination', 'total')); // Передаем сами данные переменных в вид

    }

}