<?php

namespace app\models\admin;

use RedBeanPHP\R;

class User extends \app\models\User { // Модель (класс) для работы с админкой, наследует класс User пользовательской части сайта

    public array $attributes = [ // Здесь укажем требуемые аттрибуты модели, которые нам нужны от пользователя при заполнении им формы регистрации
        'email' => '',
        'password' => '',
        'name' => '',
        'address' => '',
        'role' => '',
    ];

    public array $rules = [ // Здесь мы укажем массив с правилами
        'required' => ['email', 'password', 'name', 'address', 'role',], // Правило для обязательных полей (указываем их в массиве)
        'email' => ['email',], // Правило для проверки 'email' (должно соответствовать email адресу)
        'lengthMin' => [ // Правило для минимальной длины строки - указываем какие поля сколько должны занимать символов
            ['password', 6],
        ],
        'optional' => ['password'], // Данная опция относится к настройкам валидатора valitron. Если для формы указанные поля ('password') не будут заполнены значениями пользователем, тогда они не будут участвовать в процессе валидации (правила 'required' и 'email' к ним применяться не будут)
    ];

    public array $labels = [
        'email' => 'E-mail',
        'password' => 'Пароль',
        'name' => 'Имя',
        'address' => 'Адрес',
        'role' => 'Роль',
    ]; // Создаем свойство $labels, на выходе которого будет массив с фиксированными данными по каждому полю


    public static function isAdmin (): bool { // Метод проверки пользователя на роль администратора

        return (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'); // Если в массиве $_SESSION по ключу 'user' есть данные И в этом же массиве по ключу 'user' и 'role' == 'admin' - вернем true (авторизованный пользователь админ)
    }

    public function get_users ($start, $perpage): array { // Метод для получения информации обо всех пользователях из БД

        return R::findAll ("user", "LIMIT $start, $perpage"); // Получим данные всех пользователей. Используем метод findAll для таблицы "user" с лимитом выдачи по параметрам $start, $perpage

    }

    public function get_user ($id): array { // Получим профиль пользователя по id из БД

        return R::getRow ("SELECT * FROM user WHERE id = ?", [$id]); // Это будет одна строка, поэтому используем метод getRow. Делаем выборку из таблицы user БД по id пользователя

    }

    public function checkEmail ($user_data): bool { // Метод для проверки введенного пользователем Email в форму редактирования данных в админ-панели. На вход передаем данные профиля пользователя $user_data

        if ($user_data['email'] == $this->attributes['email']) { // Сравниваем полученный от пользователя $user_data['email'] с атрибутом модели $this->attributes['email']. Если они равны, тогда
            return true; // Вернем true
        }
        $user = R::findOne ('user', 'email = ?', [$this->attributes['email']]); // Пытаемся достать пользователя по полученному email. Объявляем переменную $user и записываем в нее данные запроса к таблице 'user' БД, где 'email = равно значению $this->attributes['email']
        if ($user) { // Если мы получили такого пользователя из БД
            $this->errors['unique'][''] = 'Этот email уже используется другим пользователем!';
            return false;
        } // Если мы не нашли пользователя с таким же email
        return true; // Вернем true
    }

}