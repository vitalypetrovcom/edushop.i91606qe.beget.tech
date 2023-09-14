<?php

namespace app\controllers;

use app\models\Main;
use RedBeanPHP\R;

/** @property Main $model */

class MainController extends AppController {

    public function indexAction () { // Метод для отработки "indexAction"

        $slides = R::findAll ('slider'); // Мы должны получить все наши слайды
        $this->set (compact ('slides')); // Передаем полученные данные в вид

    }

}