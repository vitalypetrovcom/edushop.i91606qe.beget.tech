<?php

namespace app\controllers;

use wfm\Controller;

class MainController extends Controller {

    public function indexAction () { // Метод для отработки "indexAction"
        $names = ['John', 'Dave', 'Katy'];
        $this->setMeta ('Главная страница', 'Описание ...', 'Ключевые слова ...');
//        $this->set (['test' => 'TEST VAR', 'name' => 'John']);  // Передаем переменную методом "set". Так мы можем передавать данные из контроллера в соответствующее представление views
//        $this->set (['names' => $names]);
        $this->set (compact ('names')); // Передаем данные массива "names" в представление используя функцию "compact"

    }

}