<?php

namespace app\controllers\admin;

use wfm\App;
use wfm\Cache;

class CacheController extends AppController { // Контроллер (класс) для управления кэшем в админ панели

    public function indexAction () { // Метод для показа списка кэша

        $title = 'Управление кэшем'; // Объявляем переменную $title
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title')); // Передаем саму переменную в вид

    }

    public function deleteAction () { // Метод для удаления кэша в админ-панели

        $langs = App::$app->getProperty ('languages') ; // Получаем список всех языков на сайте
       /* debug ($langs,1); // Распечатаем список всех языков*/

        $cache_key = get ('cache', 's'); // Создадим переменную - ключ кэша, которая будет указывать какой конкретно кэш мы хотим удалить. Из массива get по ключу 'cache' получим строку (тип 's')
        $cache = Cache::getInstance (); // Получаем объект кэша используя метод getInstance
        if ($cache_key == 'category') { // Если ключ-кэша равняется 'category'
            foreach ( $langs as $lang => $item) { // Пройдем в цикле по массиву языков: $lang - кад языка, $item - прочие данные языка
                $cache->delete ("ishop_menu_{$lang}"); // Удаляем данные категорий из кэша на всех языках {$lang} (ru-en)
            }
        }

        if ($cache_key == 'page') { // Если ключ-кэша равняется 'page'
            foreach ( $langs as $lang => $item) { // Пройдем в цикле по массиву языков: $lang - кад языка, $item - прочие данные языка
                $cache->delete ("ishop_page_menu_{$lang}"); // Удаляем данные категорий из кэша на всех языках {$lang} (ru-en)
            }
        }

        $_SESSION['success'] = 'Выбранный кэш удален!'; // Выдаем сообщение об успехе
        redirect (); // Делаем редирект на эту же страницу

    }

}