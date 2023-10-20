<?php

namespace app\controllers\admin;

use app\models\admin\User;
use RedBeanPHP\R;
use wfm\Pagination;

/** @property User $model */

class UserController extends AppController { // Контроллер для работы с пользовательской части админки

    public function indexAction () { // Метод для работы с пользователями в админ-панели

        // Нам нужно показать список пользователей
        $page = get ('page'); // Получаем номер текущей страницы
        $perpage = 10; // Количество записей на одной странице
        $total = R::count ('user'); // Получаем общее количество пользователей из БД
        $pagination = new Pagination($page, $perpage, $total); // Получаем объект пагинации. Передаем на вход нужные параметры $page, $perpage, $total
        $start = $pagination->getStart(); // Переменная $start - с какой позиции нам начинать выбирать записи из БД при выдаче на страницу при пагинации

        $users = $this->model->get_users ($start, $perpage); // Получаем список пользователей из БД используя метод get_users модели User

        $title = 'Список пользователей'; // Объявляем переменную $title
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title', 'users', 'pagination', 'total')); // Передаем саму переменную в вид

    }

    public function loginAdminAction () { // Метод для страницы авторизации администратора

        if ($this->model::isAdmin ()) { // Если получаем true (пользователь - админ)
            redirect (ADMIN); // Делаем редирект на главную страницу админки
        }

        $this->layout = 'login';// Указываем, что для данной страницы мы используем новый шаблон 'login'
        if (!empty($_POST)) { // Если у нас не пуст массив $_POST (пользователь отправил данные), мы будем пытаться авторизовать пользователя
            if ( $this->model->login ( true ) ) { // Если при обращении к методу модели login мы получим true
                $_SESSION['success'] = 'Вы успешно успешно авторизованы!'; // Выдаем сообщение об успехе
            } else { // Иначе
                $_SESSION['errors'] = 'Логин/пароль введены не верно!'; // Выдаем сообщение об ошибке
            }
            if ($this->model::isAdmin ()) { // Если получаем true (пользователь - админ)
                redirect (ADMIN); // Делаем редирект на главную страницу админки
            } else {
                redirect (); // Делаем редирект на эту же страницу авторизации
            }
        }
    }

    public function logoutAction () { // Метод для выхода администратора из админ-панели

        if ($this->model::isAdmin ()) { // Если получаем true (пользователь - админ)
            unset($_SESSION['user']); // Удаляем данные администратора из сессии
        }
        redirect (ADMIN . '/user/login-admin'); // Делаем редирект на страницу авторизации администратора

    }



}