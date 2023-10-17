<?php


namespace app\models\admin;


use app\models\AppModel;
use RedBeanPHP\R;
use wfm\App;

class Category extends AppModel { // Модель для работы с добавлением категорий в админ-панели. Используем AppModel пользовательской части

    public function category_validate(): bool { // Метод валидации категорий
        $errors = ''; // Объявляем переменную для ошибок
        foreach ($_POST['category_description'] as $lang_id => $item) { // Проходим в цикле по таблице 'category_description' и получаем ключ $lang_id и данные самой категории $item
            $item['title'] = trim($item['title']); // Подготовка введенного значения title обрезая концевые пробелы
            if (empty($item['title'])) { // Проверка, чтобы поле "title" было обязательно заполнено
                $errors .= "Не заполнено Наименование во вкладке {$lang_id}<br>"; // В $errors мы допишем ошибку, что поле title не заполнено
            }
        }
        if ($errors) { // Если переменная $errors не осталась пустой
            $_SESSION['errors'] = $errors; // Записываем в массив $_SESSION['errors'] значение $errors для показа
            $_SESSION['form_data'] = $_POST; // Записываем в массив $_SESSION['form_data'] данные из формы (чтобы их в будущем можно было сохранить в форме при возникновении ошибки валидации), переданные в массив $_POST
            return false; // Возвращаем false если данные НЕ прошли валидацию
        }
        return true; // Возвращаем true если данные прошли валидацию
    }

    public function save_category (): bool { // Метод для сохранения категорий из админ-панели в БД

        R::begin (); // Используем транзакцию RedBeanPHP используя try-catch
        try {

            $lang = App::$app->getProperty ('language')['id']; // Получаем текущий активный язык из контейнера App
            /*debug ($lang); // Проверка правильности выполнения*/

            $category = R::dispense ('category'); // Создаем bean $category
            $category->parent_id = post ('parent_id', 'i'); // Заполним свойства данного bean. Нам все эти данные приходят методом $_POST. Поэтому, мы берем нужные данные оттуда по ключам. По умолчанию, $_POST содержит данные в виде строки, поэтому мы укажем нужный нам формат int 'i' (может получиться либо 0 или целое значение)
            $category_id = R::store ($category); // Нам нужно получить и сохранить category_id, потому как он используется для формирования slug, который должен нам вернуть id сохраненной записи
            $category->slug = AppModel::create_slug ('category', 'slug', $_POST['category_description'][$lang]['title'], $category_id);// Теперь, получив id, мы можем в объект $category добавить slug
            $category_id = R::store ($category); // Повторно сохраняем категорию потому, что у нас уже появился slug этой категории

            foreach ($_POST['category_description'] as $lang_id => $item) { // После этой операции, мы можем пройтись по массиву category_description, чтобы получить нужные данные
                R::exec ("INSERT INTO category_description (category_id, language_id, title, description, keywords, content) VALUES (?,?,?,?,?,?)", [
                    $category_id,
                    $lang_id,
                    $item['title'],
                    $item['description'],
                    $item['keywords'],
                    $item['content'],
                ]); // Выполняем запрос на добавление заполненных данных данной категории в таблицу category_description БД
            }
            R::commit (); // Если все прошло успешно, тогда мы делаем commit этих данных
            return true; // Вернем true
        } catch (\Exception $e) { // Используем Exception
            R::rollback (); // Можем откатить транзакцию если у нас что-то пошло не по плану
            return false; // Вернем false
        }

    }

    public function update_category ($id): bool { // Метод для обновления категорий из админ-панели в БД. Принимает на вход значение $id той категории, которую нужно обновить

        R::begin (); // Используем транзакцию RedBeanPHP используя try-catch
        try {

            $category = R::load ('category', $id); // Нам нужно получить все данные категории из таблицы 'category' БД по $id категории
            if (!$category) { // Если вдруг $id был неверный и мы не нашли такую категорию
                return false;
            }

            $category->parent_id = post ('parent_id', 'i'); // Заполним свойства данного bean. Нам все эти данные приходят методом $_POST. Поэтому, мы берем нужные данные оттуда по ключам. По умолчанию, $_POST содержит данные в виде строки, поэтому мы укажем нужный нам формат int 'i' (может получиться либо 0 или целое значение)
            R::store ($category); // Повторно сохраняем категорию потому, что у нас уже появился slug этой категории

            foreach ($_POST['category_description'] as $lang_id => $item) { // После этой операции, мы можем пройтись по массиву category_description, чтобы получить нужные данные. Ключом у нас будет id ($lang_id) языка, а в $item у нас попадает вся прочая информация о категории
                R::exec ("UPDATE category_description SET title = ?, description = ?, keywords = ?, content = ? WHERE category_id = ? AND language_id = ?", [
                    $item['title'],
                    $item['description'],
                    $item['keywords'],
                    $item['content'],
                    $id,
                    $lang_id,
                ]); // Выполняем запрос на обновление заполненных данных данной категории в таблицу category_description БД
            }
            R::commit (); // Если все прошло успешно, тогда мы делаем commit этих данных
            return true; // Вернем true
        } catch (\Exception $e) { // Используем Exception
            R::rollback (); // Можем откатить транзакцию если у нас что-то пошло не по плану
            return false; // Вернем false
        }

    }

    public function get_category ($id): array { // Метод для получения данных категории товара из БД методом GET. Принимает на вход $id нужной категории и возвращает массив данных

        return R::getAssoc ("SELECT cd.language_id, cd.*, c.* FROM category_description cd JOIN category c on c.id = cd.category_id WHERE cd.category_id = ?", [$id]); // Возвращаем ассоциативный массив. Делаем выборку из БД category_description по ключу language_id (когда мы делаем выборку из БД методом getAssoc первый параметр (cd.language_id) будет у нас ключом всего ассоциативного массива, а остальные параметры, что касается категории, будет находиться внутри массива)

    }



}