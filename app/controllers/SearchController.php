<?php

namespace app\controllers;

use app\models\Search;
use wfm\App;
use wfm\Pagination;

/** @@property Search $model */

class SearchController extends AppController { // Класс (контроллер) для обработки поиска данных по сайту

    public function indexAction () { // Метод для обработки поисковых запросов

        $s = get ('s', 's');   // Получаем поисковой запрос в переменную $s методом get, мы ожидаем данные из поля с названием 's' и это должна быть строка типа 's'
        $lang = App::$app->getProperty ('language');  // Поиск должен выполняться с учетом активного языка
        $page = get ('page');  // Получим страницу, чтобы организовать постраничную навигацию (пагинацию)
        $perpage = App::$app->getProperty ('pagination');  // Получаем количество выводимых на страницу товаров
        $total = $this->model->get_count_find_products ($s, $lang); // Получим общее количество запрошенных продуктов используя модель Search и метод get_find_products
        $pagination = new Pagination($page, $perpage, $total);  // Строим пагинацию на странице создавая объект пагинации $pagination
        $start = $pagination->getStart ();  // Получим переменную $start, с какой позиции товара мы в БД начинаем выборку

        $products = $this->model->get_find_products ($s, $lang, $start, $perpage);  // Получаем товары по запросу в поисковой строке сайта
        /*debug ($products, 1); // Проверка правильности выполнения*/
        $this->setMeta (___('tpl_search_title'), ); // Передаем мета-данные товаров
        $this->set (compact ('s', 'products', 'pagination', 'total')); // Передаем аргументы: строка поиска 's', товары 'products', пагинация 'pagination', общее количество товаров 'total'



     }

}