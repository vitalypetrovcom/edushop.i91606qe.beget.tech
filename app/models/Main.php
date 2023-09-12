<?php

namespace app\models;

use RedBeanPHP\R;
use wfm\Model; // Подключение базовой модели

class Main extends Model { // Мы планируем ее использовать для контроллера "Main"

    public function get_names (): array { // Метод будет возвращать выборку всех данных (массив), используя RedBeanPHP оператор "findAll" из таблицы "names" БД "newishop"
        return R::findAll ('name');
    }

}