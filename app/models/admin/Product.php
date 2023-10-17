<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;

class Product extends AppModel { // Модель (класс) для работы с товарами в админ-панели

    public function get_products ($lang, $start, $perpage): array { // Метод для получения товаров в админ-панели из БД

        return R::getAll ("SELECT p.*, pd.title FROM product p JOIN product_description pd on p.id = pd.product_id WHERE pd.language_id = ? LIMIT $start, $perpage", [$lang['id']]); // Делаем выборку из таблиц product и product_description по заданным параметрам с получением массива с данными о таварах

    }

    public function get_downloads ($q): array { // Метод для получения списка цифровых товаров (файлов) для загрузки. Аргументом он будет принимать запрос $q и возвращать массив с данными

        $data = []; // Объявляем массив $data - по умолчанию, пустой
        $downloads = R::getAssoc ("SELECT download_id, name FROM download_description WHERE name LIKE ? LIMIT 10", ["%{$q}%"]); // В переменную $downloads попробуем получить что нам нужно запросом к БД
        if ($downloads) { // Если мы что-то получили (массив $downloads не пуст)
            $i = 0; // Объявляем счетчик
            foreach ($downloads as $id => $title) { // $id - download_id, $title - название файла для пользователя
                $data['items'][$i]['id'] = $id; // Записываем полученные данные ключ-значение в массив $data
                $data['items'][$i]['text'] = $title;
                $i++;
            }
        }
        return $data; // Вернем либо пустой массив (нет результатов), либо массив с данными

    }



}