<?php

namespace app\models;

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



}