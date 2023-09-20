<?php

namespace app\models;

use RedBeanPHP\R;

class Main extends AppModel { // Мы планируем ее использовать для контроллера "Main"

    public function get_hits ($lang, $limit): array { // Метод для выборки товаров-хитов на главной странице используя RedBeanPHP

        return R::getAll ("SELECT p.* , pd.* FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND p.hit = 1 AND pd.language_id = ? LIMIT $limit", [$lang['id']]); // Сокращенная запись наименования таблицы "p.", "*" означает все поля. Если там нужны только конкретные поля таблицы, тогда мы их перечислим через запятую: p.id, p.category_id итд.

    }

}