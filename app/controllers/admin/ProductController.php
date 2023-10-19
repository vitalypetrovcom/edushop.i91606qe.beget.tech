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

        $title = 'Список товаров'; // Объявляем переменную $title и записываем в нее значение
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title', 'products', 'pagination', 'total')); // Передаем сами данные переменных в вид

    }

    public function addAction () { // Метод добавления товара в админ-панели

        if (!empty($_POST)) { // Данные будут передаваться POST. Поэтому, как и при создании категорий, давайте проверим, если у нас не пуст массив $_POST (была отправлена форма добавления товара), тогда мы будем принимать данные

            if ($this->model->product_validate ()) { // Если мы прошли форму валидации данных при добавлении нового товара

                if ($this->model->save_product ()) { // Мы пытаемся сохранить товар
                    $_SESSION['success'] = 'Товар добавлен!'; // В массив $_SESSION['success'] запишем 'Товар добавлен!'
                } else {
                    $_SESSION['errors'] = 'Ошибка добавления товара!'; // В массив $_SESSION['errors'] запишем 'Ошибка добавления товара!'
                }

            }
           /*debug ($_POST, 1); // Проверка правильности выполнения*/

            redirect (); // Делаем редирект на ту же страницу
        }

        // В противном случае, мы будем просто показывать страницу
        $title = 'Новый товар'; // Объявляем переменную $title
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title')); // Передаем сами данные переменной в вид

    }


    public function editAction () { // Метод для редактирования информации о товаре в админ-панели

        $id = get ('id'); // По id мы достаем товар из БД. Получим id товара из массива $_GET

        if (!empty($_POST)) { // У нас данные о редактируемом товаре будут передаваться методом POST. Если массив POST не пуст (мы отправили заполненную форму редактирования товара). Тогда мы будем обрабатывать данные из формы, которые пришли POST

            if ($this->model->product_validate ()) { // Если мы прошли валидацию данных о товаре
                if ($this->model->update_product ($id)) { // Мы пытаемся обновить данные товара по $id в БД
                    $_SESSION['success'] = 'Данные о товаре обновлены!'; // В массив $_SESSION['success'] запишем 'Данные о товаре обновлены!'
                } else {
                    $_SESSION['errors'] = 'Ошибка обновления данных товара!'; // В массив $_SESSION['errors'] запишем 'Ошибка обновления данных товара!'
                }
            }
            redirect (); // Редирект на ту же страницу

        }


        $product = $this->model->get_product ($id); // Если массив POST пуст, тогда получаем данные из БД о продукте по id и записываем эти данные в переменную $product
        /*debug ($product, 1); // Проверка правильности выполнения*/

        if (!$product) { // Если у нас нет данных о товаре (массив $product пуст)
            throw new \Exception('Not found product', 404); // Выбрасываем исключение 'Not found product'
        }

        $gallery = $this->model->get_gallery ($id); // Получим картинки галереи по $id товара, если таковые есть и запишем результат в переменную $gallery

        // Возьмем мета-данные для страницы

        $lang = App::$app->getProperty ('language')['id']; // Получаем 'id' текущего активного языка сайта
        App::$app->setProperty ('parent_id', $product[$lang]['category_id']); // Для выпадающего списка категорий, нам нужно сюда передать 'parent_id'. Устанавливаем в контейнер App по ключу 'parent_id' значение 'parent_id' из массива $product[$lang]['category_id']. Это нужно для виджета Меню admin_select_tpl.php чтобы он корректно работал
        $title = 'Редактирование товара'; // Объявляем переменную $title
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title', 'product', 'gallery')); // Передаем сами данные переменных в вид

    }


    public function getDownloadAction () { // Метод для возможности прикрепления загружаемых файлов для цифровых товаров (выводим список файлов цифровых товаров из БД)

        $q = get('q', 's'); // Получаем в переменную $q из GET массива по ключу 'q' данные в виде строки 's'
        $downloads = $this->model->get_downloads($q); // Получаем данные в виде списка файлов
        echo json_encode($downloads);
        die;

    }

}