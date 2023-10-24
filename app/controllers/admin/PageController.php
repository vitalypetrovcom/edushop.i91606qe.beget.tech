<?php

namespace app\controllers\admin;

use app\models\admin\Page;
use RedBeanPHP\R;
use wfm\App;
use wfm\Pagination;

/** @property Page $model  */

class PageController extends AppController { // Контроллер (класс) для работы со страницами в админ-панели

    public function indexAction () { // Метод для отображения списка страниц сайта

        $lang = App::$app->getProperty ('language'); // Получаем текущий активный язык сайта из контейнера App

        // Нам нужна пагинация на странице
        $page = get('page'); // Нам потребуется здесь пагинация, поэтому получим GET параметр page для пагинации
        $perpage = 10; // Количество страниц на странице
        $total = R::count ('page'); // Нам нужно понять сколько всего у нас страниц
        $pagination = new Pagination($page, $perpage, $total); // Получаем объект пагинации. Передаем на вход нужные параметры $page, $perpage, $total
        $start = $pagination->getStart (); // Переменная $start - с какой позиции нам начинать выбирать записи из БД при выдаче на страницу при пагинации

        // Получаем данные о всех страницах сайта
        $pages = $this->model->get_pages ($lang, $start, $perpage); // Объявляем переменную $pages и передаем в нее используя метод модели get_pages массив с данными о всех страницах сайта

        // Устанавливаем мета-данные на страницу
        $title = 'Список страниц'; // Объявляем переменную $title и записываем в нее значение
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title', 'pages', 'pagination', 'total')); // Передаем сами данные переменных в вид
    }

    public function deleteAction () { // Метод для удаления страниц сайта из админ-панели

        $id = get ('id');// Получим id страницы из массива GET
        if ($this->model->deletePage ($id)) { // Удаляем страницу по id из двух таблиц в БД используя метод модели deletePage. Если удаление страницы прошло успешно
            $_SESSION['success'] = 'Выбранная страница удалена!'; // Выдаем сообщение об успехе
        } else { // Если удаление страницы не произошло
            $_SESSION['errors'] = 'Ошибка при удалении страницы!'; // Выдаем сообщение об ошибке
        }
        redirect (); // Редирект на ту же страницу
    }

}