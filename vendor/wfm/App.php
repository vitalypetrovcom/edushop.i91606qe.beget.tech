<?php


namespace wfm;


class App {

    public static $app; // В это свойство будет записан весь наш контейнер

    public function __construct () {

        $query = trim (urldecode ($_SERVER['QUERY_STRING']), '/');   // Текущий url-адрес

        new ErrorHandler(); // Обьект для отлавливания ошибок

        self::$app = Registry::getInstance (); // Записываем в наш контейнер обьект класса реестр "Registry" через "getInstance" и здесь нам будут доступны методы "setProperty" и "getProperty"

        $this->getParams ();

        Router::dispatch ($query); // Передаем в "Router" строку запроса пользователя "query"

    }

    protected function getParams () { // Метод для подключения каких-либо параметров для нашего фреймворка

        $params = require_once CONFIG . '/params.php'; // Можно еще добавить проверку на существование файла "params.php"

        if (!empty($params)) {
            foreach ( $params as $k => $v ) {
                self::$app->setProperty ($k, $v);     // Записываем в наш контейнер ключи и значения $k - $name, $v - $value
            }
        }
    }



}