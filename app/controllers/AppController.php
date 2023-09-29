<?php

namespace app\controllers;

use app\models\AppModel;
use app\models\Wishlist;
use app\widgets\language\Language;
use RedBeanPHP\R;
use wfm\App;
use wfm\Controller;

class AppController extends Controller { // Создаем базовый контроллер приложения "AppController" (в папке "app"), который наследует базовый контроллер фреймворка "Controller" (в папке "vendor").

    public function __construct ( $route ) { // Метод для автоматического подключения к БД
        parent::__construct ( $route );
        new AppModel(); // Создаем новый объект "AppModel" (чтобы не выдавало ошибку на странице продукта (Текст ошибки: Call to a member function getDatabase() on null, Файл - View.php, Строка - 79) без наличия соединения с БД

        App::$app->setProperty ('languages', Language::getLanguages ());  // Записываем языки в контейнер
        App::$app->setProperty ('language', Language::getLanguage (App::$app->getProperty ('languages')));   // Записываем активный язык на сайте в контейнер
//        debug (App::$app->getProperty ('languages')); // Выводим Список доступных языков
//        debug (Language::getLanguage (App::$app->getProperty ('languages'))); // Запрашиваемый пользователем в адресной строке язык
//        debug (App::$app->getProperty ('language')); // Выводим Активный язык сайта

        $lang = App::$app->getProperty ('language'); // Переменная с данными по текущему активному языку на сайте
       \wfm\Language::load ($lang['code'], $this->route);

        $categories = R::getAssoc("SELECT c.*, cd.* FROM category c 
                        JOIN category_description cd
                        ON c.id = cd.category_id
                        WHERE cd.language_id = ?", [$lang['id']]); // Получаем меню из БД используя функцию RedBeanPHP - ассоациативный массив всех категорий
        /*debug ($categories); // Проверка правильности вывода категорий*/
        App::$app->setProperty ("categories_{$lang['code']}", $categories);  // Кладем полученные категории в наш контейнер в зависимости от языка (categories_ru ИЛИ categories_en, по которым мы потом будем доставать эти данные)

        App::$app->setProperty ('wishlist', Wishlist::get_wishlist_ids ());  // В контейнер заберем все id-шники тех товаров, которые находятся в избранном. Под именем 'wishlist' в контейнер запишем то, что нам вернет метод "get_wishlist_ids"
        /*debug (App::$app->getProperty ('wishlist')); // Проверка правильности выполнения*/


    }

}