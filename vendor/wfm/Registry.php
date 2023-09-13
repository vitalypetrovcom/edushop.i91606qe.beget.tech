<?php


namespace wfm;


class Registry { // Класс Реестр - необходим для сбора(записи) всех данных от запросов пользователя в контейнер

    use TSingleton; // Подключаем трейт "TSingleton"

    protected static array $properties = []; // Для реализации трейта, нам нужен массив (контейнер), куда мы будем класть все необходимые нам данные.

    public function setProperty ($name, $value) { // Для реализации шаблона "Реестр", метод "setProperty" будет записывать в котейнер какие-либо данные, а метод "getProperty" будет получать данные из контейнера, если они есть

        self::$properties[$name] = $value;

    }

    public function getProperty ($name) {

        return self::$properties[$name] ?? null; // Если есть значение - возвращает значение, если нет - возвращает null

    }

    public function getProperties (): array // Отладочный метод, который будет возвращать массив всего, что есть в контейнере
    {
        return self::$properties;
    }




}