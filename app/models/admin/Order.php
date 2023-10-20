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

    public function get_order ($id): array { // Метод для получения информации о конкретном заказе по $id заказа

        return R::getAll ("SELECT o.*, op.* FROM orders o JOIN order_product op on o.id = op.order_id WHERE o.id = ?", [$id]); // Делаем выборку из таблиц orders и order_product из БД по условию o.id (orders id) = $id заказа
    }

    public function change_status ($id, $status): bool { // Метод для изменения текущего статуса заказа. На вход будет принимать $id заказа и статус $status, который мы хотим получить

        $status = ($status == 1) ? 1: 0; // Если текущий статус заказа равен 1, значит статус остается равным 1, в противном случае - будет 0

        R::begin (); // Используем транзакцию RedBeanPHP используя try-catch
        try {
            R::exec ("UPDATE orders SET status =? WHERE id = ?", [$status, $id]); // Запрос на обновление статуса заказа в таблице orders по id заказа в БД
            R::exec ("UPDATE order_download SET status =? WHERE order_id = ?", [$status, $id]); // Запрос на обновление статуса заказа в таблице order_download по id заказа в БД



            R::commit (); // Если все прошло успешно, тогда мы делаем commit этих данных
            return true; // Вернем true
        } catch (\Exception $e) { // Используем Exception
            R::rollback (); // Можем откатить транзакцию если у нас что-то пошло не по плану
            return false; // Вернем false
        }

    }

}