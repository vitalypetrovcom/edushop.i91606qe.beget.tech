<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;

class Product extends AppModel { // Модель (класс) для работы с товарами в админ-панели

    public function get_products ($lang, $start, $perpage): array { // Метод для получения товаров в админ-панели из БД

        return R::getAll ("SELECT p.*, pd.title FROM product p JOIN product_description pd on p.id = pd.product_id WHERE pd.language_id = ? LIMIT $start, $perpage", [$lang['id']]); // Делаем выборку из таблиц product и product_description по заданным параметрам с получением массива с данными о таварах

    }



}