<?php

namespace app\controllers;

use app\models\AppModel;
use app\widgets\language\Language;
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


    }

}