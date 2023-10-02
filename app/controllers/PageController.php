<?php

namespace app\controllers;

use app\models\Page;
use wfm\App;

/** @property Page $model */

class PageController extends AppController { // Контроллер (класс) для работы со страницами сайта

    public function viewAction () { // Метод для работы со страницами сайта

        $lang = App::$app->getProperty ('language');  // Получим данные об активном языке сайта
        $page = $this->model->get_page ($this->route['slug'], $lang); // Получаем данные страницы из модели Page методом get_page, передавая на вход $this->route['slug'] и $lang
        /*debug ($page, 1); // Проверка правильности выполнения */

        if (!$page) { // Если нет искомой страницы искомой $page
            $this->error_404 ();
            return;
        }

        $this->setMeta ($page['title'], $page['description'], $page['keywords']); // Передадим мета-данные,
        $this->set (compact ('page'));  // Передаем сами данные используя оператор compact (создает массив из полученных данных)

    }

}