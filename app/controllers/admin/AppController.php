<?php

namespace app\controllers\admin;

use app\models\admin\User;
use app\models\AppModel;
use app\widgets\language\Language;
use wfm\App;
use wfm\Controller;

class AppController extends Controller { // Контроллер (класс) для работы с админ-панелью сайта

    public false|string $layout = 'admin'; // Подключаем шаблон админ-панели сайта

    public function __construct ( $route ) {
        parent::__construct ( $route ); // Указываем маршрут к админке

        if (!User::isAdmin () && $route['action'] != 'login-admin') { // Если пользователь не администратор И маршрут в адресной строке не равняется 'login-admin'
            redirect (ADMIN . '/user/login-admin'); // Делаем редирект на страницу авторизации

        }

        new AppModel(); // Подключаем базовую модель (подключение и доступ к БД)
        App::$app->setProperty ('languages', Language::getLanguages ());  // Записываем языки в контейнер (массив всех доступных языков)
        App::$app->setProperty ('language', Language::getLanguage (App::$app->getProperty ('languages'))); // Записываем активный язык на сайте в контейнер (массив с текущим активным языком)
    }


}