<?php


namespace app\controllers\admin;


use app\models\admin\Download;
use RedBeanPHP\R;
use wfm\App;
use wfm\Pagination;

/** @property Download $model */
class DownloadController extends AppController { // Контроллер (класс) для работы с загружаемыми файлами для прикрепления их к цифровым товарам с админ-панели

    public function indexAction() { // Метод для обработки отображения прикрепляемых файлов в админ-панели
        // Покажем список файлов
        $lang = App::$app->getProperty('language'); // Получаем текущий активный язык сайта из контейнера App
        $page = get('page'); // Нам потребуется здесь пагинация, поэтому получим GET параметр page для пагинации
        $perpage = 20; // Количество товаров на странице
        $total = R::count('download'); // Нам нужно посчитать сколько всего у нас файлов для цифровых товаров (тип 'download')
        $pagination = new Pagination($page, $perpage, $total); // Получаем объект пагинации. Передаем на вход нужные параметры $page, $perpage, $total
        $start = $pagination->getStart(); // Переменная $start - с какой позиции нам начинать выбирать записи из БД при выдаче на страницу при пагинации

        $downloads = $this->model->get_downloads($lang, $start, $perpage); // Получаем список файлов для цифровых товаров из БД в переменную $downloads. На вход передаем параметры $lang, $start, $perpage
        $title = 'Файлы (цифровые товары)'; // Объявляем переменную $title и записываем в нее значение 'Файлы (цифровые товары)'
        $this->setMeta("Админка :: {$title}"); // Передаем название title в представление
        $this->set(compact('title', 'downloads', 'pagination', 'total')); // Передаем сами данные переменных в вид
    }

}