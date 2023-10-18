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

    public function product_validate (): bool { // Метод для валидации информации о товаре в админ-панели

        // Общие признаки
        $errors = ''; // Объявляем переменную $errors (по умолчанию пуста)
        if (!is_numeric (post ('price'))) { // Проверяем, является ли данные в поле 'price' числом
            $errors .= "Цена должна быть числовым значением!<br>";
        }
        if (!is_numeric (post ('old_price'))) { // Проверяем, является ли данные в поле 'old_price' числом
            $errors .= "Старая цена должна быть числовым значением!<br>";
        }

        // Частные признаки
        foreach ($_POST['product_description'] as $lang_id => $item) { // Выбираем из массива $_POST по ключу 'product_description' данные в формате ключ-данные $lang_id => $item (выбираем по языку $lang_id)
            $item['title'] = trim ($item['title']); // Убираем пробелы вначале и конце строки
            $item['exerpt'] = trim ($item['exerpt']);
            if (empty($item['title'])) { // Если $item['title'] пустой
                $errors .= "Не заполнено Наименование во вкладке {$lang_id}!<br>";
            }
            if (empty($item['exerpt'])) { // Если $item['exerpt'] пустой
                $errors .= "Не заполнено Краткое описание во вкладке {$lang_id}!<br>";
            }
        }

        if ($errors) { // Если у нас $errors не пустая
            $_SESSION['errors'] = $errors; // Запишем ошибки в массив сессии
            $_SESSION['form_data'] = $_POST; // В массиве $_SESSION['form_data'] сохраним данные $_POST из заполненной формы
            return false;
        }
        return true; // Если мы прошли проверку валидации, вернем true

    }



}