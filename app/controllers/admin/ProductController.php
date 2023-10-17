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

    public function addAction () { // Метод добавления товара в админ-панели

        if (!empty($_POST)) { // Данные будут передаваться POST. Поэтому, как и при создании категорий, давайте проверим, если у нас не пуст массив $_POST (была отправлена форма добавления товара), тогда мы будем принимать данные


        }

        // В противном случае, мы будем просто показывать страницу
        $title = 'Новый товар'; // Объявляем переменную $title
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title')); // Передаем сами данные переменной в вид

    }

    public function getDownloadAction () { // Метод для возможности прикрепления загружаемых файлов для цифровых товаров (выводим список файлов цифровых товаров из БД)

        /*$data = [  // Массив для примера возможности выбора прикрепляемого файла для цифрового товара в админ-панели
            'items' => [
                [
                    'id' => 1,
                    'text' => 'Файл 1',
                ],
                [
                    'id' => 2,
                    'text' => 'Файл 2',
                ],
                [
                    'id' => 3,
                    'text' => 'File 1',
                ],
                [
                    'id' => 4,
                    'text' => 'File 2',
                ],
            ]
        ];*/
        $q = get('q', 's'); // Получаем в переменную $q из GET массива по ключу 'q' данные в виде строки 's'
        $downloads = $this->model->get_downloads($q); // Получаем данные в виде списка файлов
        echo json_encode($downloads);
        die;

    }

}