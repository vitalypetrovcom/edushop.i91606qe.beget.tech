<?php

namespace app\models;

use RedBeanPHP\R;

class Order extends AppModel { // Модель (класс) для обработки заказа товаров пользователя

    public static function saveOrder ($data): int|false { // Метод для сохранения заказа пользователя. Принимает на вход данные $data с формы оформления заказа. Возвращать будет номер заказа или false

        // Чтобы данные не потерялись (если какой-то из SQL запросов вернет ошибку), чтобы сохранить целостность переданных пользователем данных, принято использовать механизм трансзакций в SQL. Используем RedBeanPHP и для трансзакций у него есть следующая конструкция
        R::begin (); // Вызываем метод begin
        try { // Используем блок try-catch. В этом блоке мы будем выполнять SQL запросы
            // 1. Нам нужно сохранить заказ и получить номер заказа
            $order = R::dispense ('orders'); // В переменную $order
            $order->user_id = $data['user_id']; // Мы заполним все необходимые свойства запроса $order с предыдущего шага (в RedBeanPHP называется Bean). Передаем user_id
            $order->note = $data['note']; // Передаем примечания note
            $order->total = $_SESSION['cart.sum']; // Передаем общую сумму заказа
            $order->qty = $_SESSION['cart.qty']; // Передаем общее количество товаров
            $order_id = R::store ($order); // В переменную $order_id получаем номер заказа
            self::saveOrderProduct ($order_id, $data['user_id']); // Вызовем метод сохранения товаров при оформлении заказа saveOrderProduct и передаем $order_id, $data['user_id']

            R::commit ();// Чтобы выполнить трансзакцию, мы вызываем метод commit
            return $order_id; // Вернем номер заказа
        } catch (\Exception $e) { // Здесь пойдут ошибки и исключения

            R::rollback (); // Откатим все запросы, которые были выполнены на текущий момент по данной трансзакции
            return false;
        }
    }

    public static function saveOrderProduct ($order_id, $user_id) { // Метод, который будет сохранять товары заказа. На вход передаем параметры $order_id, $user_id
        $sql_part = ''; // Будем формировать SQL запрос на основе различных параметров. Для этого, мы объявим переменную $sql_part
        $binds = []; // Объявим массив $binds. В него мы будем placeholder для подстановки в SQL запрос
        foreach ($_SESSION['cart'] as $product_id => $product) { // В цикле проходим по массиву $_SESSION['cart']

            // Если у нас цифровой товар (в массиве $product у нас будет соответствующий флаг is_download)
            if ($product['is_download']) { // Если у нас цифровой товар
                // Нам нужно получить id товара
                $download_id = R::getCell ("SELECT download_id FROM product_download WHERE product_id = ?", [$product_id]); // Объявляем переменную $download_id и запишем в нее значение id цифрового товара, который прикреплен к данному продукту

                $order_download = R::xdispense ('order_download');  // Создадим переменную объект order_download и запишем в нее значение запроса
                $order_download->order_id = $order_id; // Заполняем его соответствующими свойствами. $order_id
                $order_download->user_id = $user_id; // $user_id
                $order_download->product_id = $product_id; // $product_id
                $order_download->download_id = $download_id; // $download_id
                R::store ($order_download); // Сохраняем объект $order_download в БД
            }

            // Если у нас обычный товар. Нам нужно выгрузить информацию об обычном товаре в таблицу "order_product". Для этого нам нужны: order_id, product_id, title, slug, qty, price и sum
            $sum = $product['qty'] * $product['price']; // Создаем переменную $sum суммарной стоимости конкретного товара
            $sql_part .= "(?,?,?,?,?,?,?),"; // Будем формировать SQL запрос на основе различных параметров (?).
            $binds = array_merge($binds, [$order_id, $product_id, $product['title'], $product['slug'], $product['qty'], $product['price'], $sum]); // Объединяем (дозаписываем) массив $binds со следующим массивом данных товара
        }

            $sql_part = rtrim ($sql_part, ','); // Удаляем лишнюю запятую в конце полученного массива $sql_part
            R::exec ("INSERT INTO order_product (order_id, product_id, title, slug, qty, price, sum) VALUES $sql_part", $binds); // Выполняем запрос методом exec - сохраняем данные товаров с корзины на странице оформления заказа в БД

    }

}