<?php

namespace app\models;

use Cassandra\Index;
use RedBeanPHP\R;

class Search extends AppModel { // Модель (класс) для обработки поиска на сайте

    public function get_count_find_products ($s, $lang): int { // Метод, чтобы получить количество найденных продуктов - нужно для пагинации. Будет принимать аргументами поисковый запрос $s и язык $lang.

        return R::getCell ("SELECT COUNT(*) FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND pd.language_id = ? AND pd.title LIKE ?", [$lang['id'], "%{$s}%"]);

    }

    public function get_find_products ($s, $lang, $start, $perpage): array { // Метод будет возвращать сами товары в виде массива товаров. Будет принимать аргументами поисковый запрос $s, язык $lang, с какого товара выводить результат поиска $start и количество товаров выводимых на страницу $perpage

        return R::getAll ("SELECT p.*, pd.* FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND pd.language_id = ? AND pd.title LIKE ? LIMIT $start, $perpage", [$lang['id'], "%{$s}%"]);

    }

}