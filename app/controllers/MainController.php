<?php

namespace app\controllers;

use app\models\Main;
use RedBeanPHP\R;
use wfm\Controller;

/** @property Main $model */

class MainController extends Controller {

    public function indexAction () { // Метод для отработки "indexAction"
//        $names = ['John', 'Dave', 'Katy'];

        $names = $this->model->get_names (); // Выборка всех данных, используя RedBeanPHP оператор "findAll" из таблицы "names" БД "newishop"

        $one_name = R::getRow( 'SELECT * FROM name WHERE id = 2');

        $this->setMeta ('Главная страница', 'Описание ...', 'Ключевые слова ...');
//        $this->set (['test' => 'TEST VAR', 'name' => 'John']);  // Передаем переменную методом "set". Так мы можем передавать данные из контроллера в соответствующее представление views
//        $this->set (['names' => $names]);
        $this->set (compact ('names')); // Передаем данные массива "names" в представление используя функцию "compact"

    }

}