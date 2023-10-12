<?php

namespace app\models\admin;

class User extends \app\models\User { // Модель (класс) для работы с админкой, наследует класс User пользовательской части сайта

    public static function isAdmin (): bool { // Метод проверки пользователя на роль администратора

        return (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'); // Если в массиве $_SESSION по ключу 'user' есть данные И в этом же массиве по ключу 'user' и 'role' == 'admin' - вернем true (авторизованный пользователь админ)
    }

}