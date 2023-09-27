<?php

namespace app\models;

use RedBeanPHP\R;

class Product extends AppModel { // Модель для работы со страницами товара

    public function get_product ($slug, $lang):array { // Метод, с помощью которого нам нужно объединить таблицы product и product_description, чтобы получить нужные данные по входным данным $slug и $lang
        return R::getRow ("SELECT p.*, pd.* FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND p.slug = ? AND pd.language_id = ?", [$slug, $lang['id']]); // Метод для получения данных товара из БД

    }

    public function get_gallery ($product_id):array {  // Метод, который будет получать изображения галереи для конкретного товара
        return R::getAll ("SELECT * FROM product_gallery WHERE product_id = ?", [$product_id]); // Метод для получения всех изображений галереи для конкретного товара
    }



}