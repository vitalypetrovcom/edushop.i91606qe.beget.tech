<?php

namespace app\models;

use RedBeanPHP\R;

class User extends AppModel { // Модель (класс) для работы с данными пользователей из БД

    public array $attributes = [ // Здесь укажем требуемые аттрибуты модели, которые нам нужны от пользователя при заполнении им формы регистрации
        'email' => '',
        'password' => '',
        'name' => '',
        'address' => '',
    ];

    public array $rules = [ // Здесь мы укажем массив с правилами
        'required' => ['email', 'password', 'name', 'address',], // Правило для обязательных полей (указываем их в массиве)
        'email' => ['email',], // Правило для проверки 'email' (должно соответствовать email адресу)
        'lengthMin' => [ // Правило для минимальной длины строки - указываем какие поля сколько должны занимать символов
            ['password', 6],
        ],

    ];

    public array $labels = [
        'email' => 'tpl_signup_email_input',
        'password' => 'tpl_signup_password_input',
        'name' => 'tpl_signup_name_input',
        'address' => 'tpl_signup_address_input',
    ]; // Создаем свойство $labels, на выходе которого будет массив с переводными данными по каждому полю

    public static function checkAuth (): bool { // Метод проверки аутентификации пользователя на сайте

        return isset($_SESSION['user']); // Вернем значение из массива $_SESSION по ключу 'user'

    }

    public function checkUnique ($text_error = ''): bool { // Метод проверки на уникальность вводимого email в пользовательской форме. Принимает на вход параметр $text_error = '' (можем передать какой-либо аргумент для ошибки)

        $user = R::findOne ('user', 'email = ?', [$this->attributes['email']]);  // Для того чтобы проверить есть ли в БД уже такой email, используем RedBeanPHP метод findOne (в таблице 'user' ищем 'email = ?' со значением $this->attributes['email'] )
        if ($user) { // Если мы что-то получаем в массиве $user
            $this->errors['unique'][] = $text_error ? : ___ ('user_signup_error_email_unique');   // Записываем сообщение об ошибке 'Этот email уже зарегистрирован'. Сообщения об ошибках у нас хранятся в многомерном массиве errors с созданным нами ключом 'unique'.
            return false;
        }
        return true; // Если массив пуст (такого email в БД нет)
    }



}