<?php

namespace app\models;

class User extends AppModel { // Модель (класс) для работы с данными пользователей из БД

    public array $attributes = [ // Здесь укажем требуемые аттрибуты модели, которые нам нужны от пользователя при заполнении им формы регистрации
        'email' => '',
        'password' => '',
        'name' => '',
        'address' => '',
    ];

    public static function checkAuth (): bool { // Метод проверки аутентификации пользователя на сайте

        return isset($_SESSION['user']); // Вернем значение из массива $_SESSION по ключу 'user'

    }



}