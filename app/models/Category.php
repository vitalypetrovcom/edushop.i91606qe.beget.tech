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

    public function get_products ($ids, $lang, $start, $perpage): array { // Метод для получения товаров конкретной выбранной категории. Аргументами передаем id-шники $ids и язык $lang. Дополнительно, передаем аргументы $start, $perpage

        $sort_values = [
            'title_asc' => 'ORDER BY title ASC',
            'title_desc' => 'ORDER BY title DESC',
            'price_asc' => 'ORDER BY price ASC',
            'price_desc' => 'ORDER BY price DESC',
        ]; // Объявляем массив $sort_values и в нем перечислим допустимые варианты опции сорт (app/views/Category/view.php). Такой способ называется "белый список": мы в массиве перечисляем допустимые значения и когда значение приходит от клиента, мы сравниваем со значением из нашего списка. Если находим совпадение - используем это значение, если нет - мы его игнорируем. Такой вариант с белым списком считается самым надежным в плане взлома. В массиве, мы перечислим части наших SQL запросов

        $order_by = ''; // Создадим переменную для проверки $order_by
        if (isset($_GET['sort']) && array_key_exists ($_GET['sort'], $sort_values) ) { // Проверка, если у нас существует в массиве $_GET ключ 'sort' И при этом, у нас есть такое значение в массиве $sort_values
            $order_by = $sort_values[$_GET['sort']]; // В переменную $order_by запишем значение из массива $sort_values по ключу $_GET['sort']
        }

        return R::getAll ("SELECT p.*, pd.* FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND p.category_id IN ($ids) AND pd.language_id = ? $order_by LIMIT $start, $perpage", [$lang['id']]); // Используем RedBeanPHP и метод getAll

    }

    public function get_count_products ($ids): int { // Метод для получения общего количества товаров в данной категории. На вход принимает id-шники товаров $ids

        return R::count ('product', "category_id IN ($ids) AND status = 1");  // Получаем общее количество товаров в данной категории и возвращаем его. Используем RedBeanPHP и функцию "count": передаем название таблицы в БД 'product', добавляем SQL запрос с учетом статуса товара

    }



}