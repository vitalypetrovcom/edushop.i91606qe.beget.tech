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
            /*debug ($data);
            debug ($this->model->attributes); // Проверка правильности выполнения*/

            if (!$this->model->validate ($data) || !$this->model->checkUnique ()) { // Проверим, что вернул у нас метод validate (bool)
                $this->model->getErrors (); // Если не прошли валидацию (есть ошибки). Мы должны их показать используя модель и метод getErrors (запишет ошибки валидации в сессию)

                $_SESSION['form_data'] = $data; // Создаем элемент сессии с ключом 'form-data' для хранения вводимых пользователем данных в форму и запишем туда данные из массива $data

            } else { // Иначе

                $this->model->attributes['password'] = password_hash ($this->model->attributes['password'], PASSWORD_DEFAULT); // Перед сохранением пароля из пользовательской формы $this->model->attributes['password'] в БД, мы хешируем пароль функцией password_hash алгоритмом PASSWORD_DEFAULT
                if ($this->model->save ('user')) { // Если мы успешно записали данные заполненной пользовательской формы в БД
                    $_SESSION['success'] = ___ ('user_signup_success_register'); // Если прошли валидацию (нет ошибок). Помещаем в массив $_SESSION по ключу 'success' строку сообщения 'Учетная запись была создана'
                } else {
                    $_SESSION['errors'] = ___ ('user_signup_error_register'); // Если не прошли валидацию выдаем ошибку. Помещаем в массив $_SESSION по ключу 'errors' строку сообщения 'Ошибка регистрации'
                }



            }
            redirect (); // Делаем редирект на эту же страницу чтобы не было повторной отправки формы
        }


        $this->setMeta (___ ('tpl_signup')); // Проставим мета-данные страницы

    }

}