<?php

namespace app\models;

use RedBeanPHP\R;
use wfm\App;

class Category extends AppModel { // Модель (класс) для обработки страниц категорий товаров

    public function get_category ($slug, $lang): array { // Метод для получения данных о конкретной категории. Передаваемые аргументы $slug и $lang конкретной категории

        return R::getRow ("SELECT c.*, cd.* FROM category c JOIN category_description cd on c.id = cd.category_id WHERE c.slug = ? AND cd.language_id = ?", [$slug, $lang['id']]); // Получаем данные категории используя RedBeanPHP метод getRow позволит нам вернуть одну запись

    }

    public function getIds ($id): string { // Метод для проверки наличия вложенных категорий. На вход аргументом передаем $id категории

        $lang = App::$app->getProperty ('language')['code']; // Получаем активный язык сайта
        $categories = App::$app->getProperty ("categories_{$lang}"); // Получаем категории на основе языка в виде массива
        $ids = '';  // Сделаем рекурсию: создаем переменную $ids (по умолчанию пустая строка), мы в нее будем складывать id перечисленные через запятую. Затем мы эту строку подставим в SQL запрос в условие IN()

        foreach ( $categories as $k => $v ) {
            if ($v['parent_id'] == $id) { // Если $v['parent_id'] будет равно текущему $id - это значит, что мы нашли потомка
                $ids .= $k . ','; // Мы в переменную $ids добавляем все что там находится $k плюс запятая ','
                $ids .= $this->getIds ($k);  // Будем рекурсивно вызывать метод getIds и передавать новый id $k
            }
        }
        return $ids; // Возвращаем строку id-шников
    }

    public function get_products ($ids, $lang): array { // Метод для получения товаров конкретной выбранной категории. Аргументами передаем id-шники $ids и язык $lang

        return R::getAll ("SELECT p.*, pd.* FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND p.category_id IN ($ids) AND pd.language_id = ?", [$lang['id']]); // Используем RedBeanPHP и метод getAll

    }



}