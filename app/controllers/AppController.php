<?php

namespace app\controllers;

use wfm\Controller;

class AppController extends Controller { // Создаем базовый контроллер приложения "AppController" (в папке "app"), который наследует базовый контроллер фреймворка "Controller" (в папке "vendor").

    public function __construct ( $route ) { // Метод для автоматического подключения к БД
        parent::__construct ( $route );
    }

}