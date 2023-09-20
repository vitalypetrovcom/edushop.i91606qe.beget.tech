<?php

namespace wfm;

class Language { // Класс для обработки переводов страниц сайта

    public static array $lang_data = []; // Свойство, массив со всеми переводными фразами страницы (фразы шаблона и вида)
    public static array $lang_layout = []; // Свойство, массив с переводными фразами шаблона
    public static array $lang_view = []; // Свойство, массив с переводными фразами представления (вида)

    public static function load ($code, $view) { // Метод будет загружать переводные фразы в массивы "$lang_data, $lang_layout, $lang_view" для использования. "$code" - код языка, "$view" - маршрут (путь) к файлу.

        $lang_layout = APP . "/languages/{$code}.php"; // Путь к файлу нужного языкового шаблона
        $lang_view = APP . "/languages/{$code}/{$view['controller']}/{$view['action']}.php"; // Путь к файлу нужного языкового вида
        if (file_exists ($lang_layout)) { // Если файл существует
            self::$lang_layout = require_once $lang_layout; // Помещаем в него путь к $lang_layout
        }
        if (file_exists ($lang_view)) { // Если файл существует
            self::$lang_view = require_once $lang_view; // Помещаем в него путь к $lang_view
        }
        self::$lang_data = array_merge (self::$lang_layout, self::$lang_view); // Объединяем два массива в один
    }

    public static function get ($key) { // Метод по ключу (псевдоним переводной фразы) будет возвращать значение (саму переводную фразу на нужном языке) из массива "$lang_data"

        return self::$lang_data[$key] ?? $key; // Если есть ключ, возвращаем значение. Если ключа нет - возвращаем сам ключ (фразу без перевода)

    }


}