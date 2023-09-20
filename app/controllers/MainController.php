<?php

namespace app\controllers;

use app\models\Main;
use RedBeanPHP\R;
use wfm\App;

/** @property Main $model */

class MainController extends AppController {

    public function indexAction () { // Метод для отработки "indexAction"

       $lang = App::$app->getProperty ('language'); // Переменная с данными по активному текущему языку сайта

        $slides = R::findAll ('slider'); // Мы должны получить все наши слайды

        $products = $this->model->get_hits ($lang, 6); // Переменная для хранения товаров-хитов (выборка из БД по языку и количеству в выдаче)


        $this->set (compact ('slides', 'products')); // Передаем полученные данные в вид

        $this->setMeta (___ ('main_index_meta_title'), ___ ('main_index_meta_description'), ___ ('main_index_meta_keywords'));

    }

}