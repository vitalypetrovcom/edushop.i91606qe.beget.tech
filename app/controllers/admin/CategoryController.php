<?php

namespace app\controllers\admin;

class CategoryController extends AppController { // Контроллер для работы с категориями товаров в админке

    public function indexAction () { // Метод для работы с категориями товаров в админке

        $title = 'Категории'; // Объявляем переменную $title
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title')); // Передаем саму переменную в вид

    }

}