<?php

namespace app\controllers;

use app\models\User;

/** @property User $model */

class UserController extends AppController { // Контроллер (класс) для работы с пользователями

    public function signupAction () { // Метод для регистрации пользователей

        if (User::checkAuth ()) { // Проверяем, авторизован ли пользователь
            redirect (base_url ()); // Если пользователь авторизован, делаем редирект на главную страницу
        }

        if (!empty($_POST)) { // Проверяем, передали ли мы методом POST данные на сервер. Если массив $_POST не пуст, мы будем работать с переданными данными (передали данные или как минимум, нажали кнопку "отправить")

            $data = $_POST;  // Заберем данные из массива $_POST
            $this->model->load ($data);  // Загружаем данные в модель User методом load
            debug ($data);
            debug ($this->model->attributes); // Проверка правильности выполнения


        }

        $this->setMeta (___ ('tpl_signup')); // Проставим мета-данные страницы

    }

}