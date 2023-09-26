<?php

namespace app\models;

use wfm\App;

class Breadcrumbs extends AppModel { // Класс для работы с хлебными крошками

    public static function getBreadcrumbs ($category_id, $name = ''): string { // Метод для получения хлебных крошек. На вход передаем два параметра: $category_id - id категории товара и на основании этого, получать "родителей" и "потомков" для цепочки хлебных крошек И $name нужен для того, чтобы можно было продолжить хлебные крошки (имели возможность добавить в конец хлебных крошек место, где мы сейчас находимся - какая-то строка)

        $lang = App::$app->getProperty('language')['code']; // Получить активный язык сайта
        $categories = App::$app->getProperty("categories_{$lang}"); // Получить все родительские категории

        $breadcrumbs_array = self::getParts($categories, $category_id); // Получаем данные из массива $breadcrumbs_array по ключам $categories и $category_id
       /* debug ($breadcrumbs_array); // Выводим результирующий массив для конкретного товара*/
        /*debug ($categories); // Выводим массив всех категорий*/


        $breadcrumbs = "<li class='breadcrumb-item'><a href='" . base_url() . "'>" . ___('tpl_home_breadcrumbs') . "</a></li>"; // Назначение переменной $breadcrumbs, в которую мы будем класть сформированный html код цепочки хлебных крошек. Первое, что нам надо вывести - иконка и ссылка на главную страницу

        if ($breadcrumbs_array) { // Мы проверяем, если у нас не пуст массив $breadcrumbs_array, тогда
            foreach ($breadcrumbs_array as $slug => $title) { // Мы проходим в цикле по нему
                $breadcrumbs .= "<li class='breadcrumb-item'><a href='category/{$slug}'>{$title}</a></li>"; // Выводим элементы массива ключ $slug (slug категории) и значение $title (название категории)
            }
        } // Пристыковываем еще одну часть цепочки к иконке домика в хлебных крошках

        if ($name) { // Затем, пристыковываем название товара, если мы находимся на странице товара и нам передана переменная $name
            $breadcrumbs .= "<li class='breadcrumb-item active'>$name</li>";
        }
        return $breadcrumbs; // Возвращаем полную цепочку хлебных крошек от главной страницы до страницы конкретного товара
    }

    public static function getParts($cats, $id): array|false // Метод для получения частей хлебных крошек. Передаем на вход категории $cats и id $id
    {
        if (!$id) { // Если мы не передали сюда $id товара, тогда хлебные крошки мы получить не сможем и возвращаем false
            return false;
        }
        $breadcrumbs = []; // Объявляем пустой массив $breadcrumbs, куда мы будем складывать хлебные крошки

        foreach ($cats as $k => $v) { // Проходим в цикле по массиву категорий $cats и получаем ключ $k и значение $v

            if (isset($cats[$id])) { // Проверяем, существует ли в данном массиве $cats элемент с таким id "$cats[$id]"

                $breadcrumbs[$cats[$id]['slug']] = $cats[$id]['title']; // Если существует такой id, тогда мы в пустой массив $breadcrumbs по ключу $cats[$id]['slug'] кладем значение $cats[$id]['title']. Получаем массив Array ([noutbuki] => Ноутбуки, [mac] => Mac)


                $id = $cats[$id]['parent_id']; // Затем в переменную $id мы записываем родительской категории товара $cats[$id]['parent_id'];

            } else { // Если элемент с указанным id не существует, тогда мы завершим цикл
                break;
            }
        }
        return array_reverse($breadcrumbs, true); // Возвращаем перевернутый массив $breadcrumbs с точки зрения родитель -> потомок: (например, Ноутбуки -> Mac)
    }




}

