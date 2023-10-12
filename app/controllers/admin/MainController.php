<?php

namespace app\controllers\admin;

use RedBeanPHP\R;

class MainController extends AppController { // Контроллер (класс) для работы с главной страницей админ-панели сайта

    public function indexAction () { // Метод для обработки главной страницы админки

        $orders = R::count ('orders'); // Получим общее количество заказов используя RedBeanPHP и метод count
        /*debug ($orders); // Проверка правильности кода*/
        $new_orders = R::count ('orders', 'status = 0'); // Получим количество заказов, которые являются новыми (необработанные)
        $users = R::count ('user'); // Получим общее количество зарегистрированных пользователей на нашем сайте
        $products = R::count ('product'); // Получим общее количество товаров на нашем сайте

        $title = 'Главная страница';
        $this->setMeta ('Админка :: Главная страница');
        $this->set (compact ('title', 'orders', 'new_orders', 'users', 'products'));

    }

}