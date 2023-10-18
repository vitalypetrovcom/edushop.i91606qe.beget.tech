<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;
use wfm\App;

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

    public function save_product (): bool { // Метод для сохранения товара из админ-панели в БД

        R::begin (); // Используем транзакцию RedBeanPHP используя try-catch
        try {

            $lang = App::$app->getProperty ('language')['id']; // Получаем текущий активный язык из контейнера App
            /*debug ($lang); // Проверка правильности выполнения*/

            // Выгружаем данные в таблицу product
            $product = R::dispense ('product'); // Создаем bean $product
            $product->category_id = post ('parent_id', 'i'); // Заполним свойства данного bean. Нам все эти данные приходят методом $_POST. Поэтому, мы берем нужные данные оттуда по ключам. По умолчанию, $_POST содержит данные в виде строки, поэтому мы укажем нужный нам формат int 'i' (может получиться либо 0 или целое значение)
            $product->price = post ('price', 'f'); // 'f' тип float
            $product->old_price = post ('old_price', 'f'); // 'f' тип float
            $product->status = post ('status') ? 1 : 0; // Если статус есть - 1, нет - 0
            $product->hit = post ('hit') ? 1 : 0;
            $product->img = post ('img') ?: NO_IMAGE; // Если картинка есть - берем ее, если нет - константа NO_IMAGE
            $product->is_download = post ('is_download') ? 1 : 0; // Если товар цифровой - 1, если нет - 0

            $product_id = R::store ($product); // Нам нужно получить и сохранить $product_id, потому как он используется для формирования slug, который должен нам вернуть id сохраненной записи

            $product->slug = AppModel::create_slug ('product', 'slug', $_POST['product_description'][$lang]['title'], $product_id);// Теперь, получив id, мы можем в объект $product добавить slug
            R::store ($product); // Повторно сохраняем товар в таблицу product БД потому, что у нас уже появился slug этого товара

            // Выгружаем данные в таблицу product_description
            foreach ($_POST['product_description'] as $lang_id => $item) { // После этой операции, мы можем пройтись по массиву product_description, чтобы получить нужные данные
                R::exec ("INSERT INTO product_description (product_id, language_id, title, content, exerpt, keywords, description) VALUES (?,?,?,?,?,?,?)", [
                    $product_id,
                    $lang_id,
                    $item['title'],
                    $item['content'],
                    $item['exerpt'],
                    $item['keywords'],
                    $item['description'],
                ]); // Выполняем запрос на добавление заполненных данных товара в таблицу product_description БД
            }

            // Выгружаем данные в таблицу product_gallery (если прикрепляли дополнительные картинки к основному товару)
            if (isset($_POST['gallery']) && is_array ($_POST['gallery'])) { // Если есть дополнительные картинки
                $sql = "INSERT INTO product_gallery (product_id, img) VALUES "; // Сформируем SQL запрос и дополним его в цикле
                foreach ($_POST['gallery'] as $item) { // $item - путь к картинке
                    $sql .= "({$product_id}, ?),";
                }
                $sql = rtrim ($sql, ','); // Убираем лишнюю последнюю запятую
                R::exec ($sql, $_POST['gallery']); // Выполняем SQL запрос. В $_POST['gallery'] у нас будут перечислены пути к картинкам, которые мы подставим в запрос вместо знака вопрос "?"

            }

            // Выгружаем данные в таблицу product_download (если товар цифровой и к нему прикреплен файл для скачивания)
            if ($product->is_download) { // Если у нас товар цифровой
                $download_id = post ('is_download', 'i'); // В переменную $download_id запишем числовое 'i' значение (id файла, который прикреплен к цифровому товару)
                R::exec ("INSERT INTO product_download (product_id, download_id) VALUES (?,?)", [$product_id, $download_id]); // Выполняем запрос к БД на запись указанных данных в таблицу product_download
            }

            R::commit (); // Если все прошло успешно, тогда мы делаем commit этих данных
            return true; // Вернем true
        } catch (\Exception $e) { // Используем Exception
            R::rollback (); // Можем откатить транзакцию если у нас что-то пошло не по плану
            $_SESSION['form_data'] = $_POST; // Записываем заполненные данные формы в массив сессии

            /*debug ($e,1); // Распечатаем объект $e кода ошибки*/

            return false; // Вернем false
        }

    }



}