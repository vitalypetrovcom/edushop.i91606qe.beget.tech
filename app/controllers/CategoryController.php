<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Category;
use wfm\App;
use wfm\Pagination;

/** @property Category $model */ // Указываем, что у нас есть свойство соответствующей модели

class CategoryController extends AppController { // Контроллер (класс) для обработки страниц категорий товаров

    public function viewAction () { // Метод для получения данных категории и данные товаров, относящихся к данной категории

        $lang = App::$app->getProperty ('language');  // Учитываем активный язык сайта
        $category = $this->model->get_category ($this->route['slug'], $lang); // Получаем данные категории
        /*debug ($category); // Проверка правильности выполнения*/

        if (!$category) { // Проверка запроса на наличие существующих категорий товаров
            $this->error_404 (); // Если нет, функция тогда error_404
            return; // Завершаем дальнейшее выполнение кода
        }

        $breadcrumbs = Breadcrumbs::getBreadcrumbs ($category['id']);  // Устанавливаем хлебные крошки на страницу категории
        /*debug ($breadcrumbs); // Проверка правильности выполнения*/
        /*$cats = App::$app->getProperty ("categories_{$lang['code']}"); // Получаем категории из контейнера
        debug ($cats); // Проверка правильности выполнения*/

        $ids = $this->model->getIds ($category['id']); // Вызываем метод для получения вложенных категорий getIds. На вход аргументом передаем $id текущей категории
        $ids = !$ids ? $category['id'] : $ids . $category['id']; // Добавляем $id текущей категории: Если мы не получили вложенных категорий, тогда мы кладем туда текущую категорию $category['id']. Иначе, мы добавляем в $ids переменную текущей категории $category['id']
        /*var_dump ($ids); // Проверка правильности выполнения*/

        $page = get ('page'); // Получаем номер страницы используя функцию get по ключу 'page'.

        $perpage = App::$app->getProperty ('pagination');  // Получаем из контейнера значение 'pagination' (количество выводимых товаров на страницу из конфига) и записываем его в переменную $perpage

        $total = $this->model->get_count_products ($ids); // Вызываем метод "get_count_products" из модели "Category" для получения общего количества товаров в данной категории и записываем его в переменную $total
        /*var_dump ($page, $perpage, $total); // Проверка правильности выполнения*/

        $pagination = new Pagination($page, $perpage, $total); // Получаем объект "$pagination", передавая на ход аргументы $page, $perpage, $total

        $start = $pagination->getStart ();  // У объекта $pagination мы должны вызвать метод "getStart", который вернет нам значение, с какого товара нужно начинать выборку в БД
        /*var_dump ($start); // Проверка правильности выполнения
        echo $pagination;  // Благодаря магическому методу toString вызываем пагинацию для проверки правильности отображения на странице */


        $products = $this->model->get_products ($ids, $lang, $start, $perpage);  // Получим товары используя метод модели get_products. Аргументами на вход передаем $ids и $lang. Дополнительно, передаем аргументы $start, $perpage
        /*debug ($products); // Проверка правильности выполнения*/
        $this->setMeta ($category['title'], $category['description'], $category['keywords']);  // Передаем мета-данные
        $this->set (compact ('products', 'category', 'breadcrumbs', 'total', 'pagination'));  // Передаем обычные данные: информация о товарах 'products', информация о категории 'category', хлебные крошки 'breadcrumbs'. Дополнительно, передаем аргументы общее количество товаров 'total', построенная пагинация 'pagination'


    }

}