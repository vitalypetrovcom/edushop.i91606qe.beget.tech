<?php

namespace app\controllers;

use app\models\Main;
use RedBeanPHP\R;

/** @property Main $model */

class MainController extends AppController {

    public function indexAction () { // Метод для отработки "indexAction"

        $slides = R::findAll ('slider'); // Мы должны получить все наши слайды

        $products = $this->model->get_hits (1, 6); // Переменная для хранения товаров-хитов (выборка из БД по языку и количеству в выдаче)


        $this->set (compact ('slides', 'products')); // Передаем полученные данные в вид

        $this->setMeta ("Главная страница", 'Описание ...', 'Ключевые слова ...');

    }

}