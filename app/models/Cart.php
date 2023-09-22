<?php

namespace app\models;

use RedBeanPHP\R;

class Cart extends AppModel { // Модель для работы с корзиной товаров

    public function get_product ($id, $lang): array { // Метод получения информации о продукте по $id, $lang

        return R::getRow ("SELECT p.*, pd.* FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND p.id = ? AND pd.language_id = ?", [$id, $lang['id']]); // Метод вернет одну строку с информацией о продукте

    }


}