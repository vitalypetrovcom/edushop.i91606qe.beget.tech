<?php


namespace app\models\admin;


use app\models\AppModel;
use RedBeanPHP\R;

class Order extends AppModel { // Модель (класс) для работы с заказами в админ-панели

    public function get_orders ( $start, $perpage, $status ): array { // Метод для получения списка заказов из БД. На вход передаем параметры $start, $perpage, $status
        if ( $status ) { // Если значение переменной $status НЕ пустая строка
            return R::getAll ( "SELECT * FROM orders WHERE $status ORDER BY id DESC LIMIT $start, $perpage" );
        } else { // Если значение переменной $status пустая строка
            return R::getAll ( "SELECT * FROM orders ORDER BY id DESC LIMIT $start, $perpage" );
        }
    }

}