<?php

namespace wfm;

use RedBeanPHP\R; // Подключаем пространство имен "RedBeanPHP"

class Db { // Данный класс будет реализовывать паттерн "Singleton" - от него можно будет создать только один объект потому, как у нас может в приложении для одной страницы выполняться 10-100 запросов и создавать такое количество объектов не допустимо. У нас в приложении будет только один объект подключения к БД.

    use TSingleton; // Подключаем трейт "TSingleton", который возвращает новый объект через конструктор "__construct" если объекта еще не было создано.

    private function __construct () { // Для подключения используем приватный конструктор, чтобы нельзя было использовать его для создания объекта через оператор "new."
        $db = require_once CONFIG . '/config_db.php'; // Настройки подключения к БД
        R::setup ($db['dsn'], $db['user'], $db['password']); // Для подключения к БД мы используем метод "setup"
        if (!R::testConnection ()) { // Для проверки правильности установления соединения в RedBeanPHP используется метод "testConnection" (:bool)
            throw new \Exception('No connection to DB', 500); // Если подключение не было установлено, выбрасываем исключение
        }

        R::freeze (true); // Чтобы заморозить (зафиксировать) схему БД, мы используем метод "freeze"
        if (DEBUG) { // Если у нас включен режим "DEBUG"
            R::debug (true, 3); // В RedBeanPHP для отладки мы используем метод "debug" (собирает sql запросы которые он будет выполнять, он их собирает в массив и вернет нам этот массив). Этот массив нам нужен только в том случае, если у нас включен режим отладки, чтобы видеть эти sql запросы. В режиме продакшн нам эти sql запросы не нужны.
        }

        R::ext('xdispense', function( $type ){ // Используем метод ext RedBeanPHP для того, чтобы обойти ошибку RedBeanPHP при работе с таблицами БД, название которых имеет нижнее подчеркивание (например: name_id)
            return R::getRedBean()->dispense( $type );
        });



    }

}