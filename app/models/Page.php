<?php

namespace app\models;

use RedBeanPHP\R;

class Page extends AppModel { // Модель (класс) для получения данных из БД для страниц сайта

    public function get_page ($slug, $lang): array { // Метод для получения данных из БД для страниц сайта. Принимает аргументами $slug страницы и язык $lang, на котором ее нужно показать - возвращает массив данных

        return R::getRow ("SELECT p.*, pd.* FROM page p JOIN page_description pd on p.id = pd.page_id WHERE p.slug = ? AND pd.language_id = ?", [$slug, $lang['id']]); // Это будет одна страница, поэтому мы используем метод getRow RedBeanPHP

    }


}