<?php

namespace app\controllers;

use wfm\App;

class LanguageController extends AppController { // Контроллер приложения для работы с языками

    public function changeAction () {

        $lang = get ('lang', 's'); // В данном случае, будем использовать вместо "$_GET['lang']" функцию "get" с ключом 'lang', которая должна быть именно строкой 's'.
        /*Если у нас в массиве "$_GET['lang']" что-то есть, мы это передаем в $lang, если нет, запишем в $lang null.*/

        if ($lang) { // Если что-то пришло
            if (array_key_exists ($lang, App::$app->getProperty ('languages'))) { // Проверим, есть ли запрашиваемый язык в списке доступных языков сайта

                $url = trim(str_replace (PATH, '', $_SERVER['HTTP_REFERER']), '/'); // Отрезаем базовый URL


               $url_parts = explode ('/', $url,2); // Разбиваем полученный адрес на 2 части: 1 часть - возможный бывший язык (например, en), 2 часть - все остальное (например, product/canon-eos-5d)
                /*var_dump ('http://new-ishop.loc/en/product/apple', $url_parts);*/

                if (array_key_exists ($url_parts[0], App::$app->getProperty ('languages'))) { // Ищем 1 часть (бывший язык) в массиве языков сайта
                    if ($lang != App::$app->getProperty ('language')['code']) {
                        $url_parts[0] = $lang; // Если есть, присваиваем 1 части новый язык если он не является базовым
                        } else { // Если это базовый язык, мы удаляем язык из адреса url
                            array_shift ($url_parts); // Удаляем первый элемент массива
                    }

                } else { // присваиваем 1 части новый язык если он не является базовым
                    if ($lang != App::$app->getProperty ('language')['code']) {
                        array_unshift ($url_parts, $lang); // Добавляем первый элемент массива
                    }
                }
                /*var_dump ($url_parts);
                die;*/

                $url = PATH . '/' . implode ('/', $url_parts);  // Соберем из массива "$url_parts" строку с разделителем "/"
                /*var_dump ('http://new-ishop.loc/en/product/apple');
                var_dump ($url); die;*/
                redirect ($url); // Редирект на $url

            }
        }
        redirect (); // Если ничего не пришло, делаем редирект на страницу, с которой пришли

    }

}