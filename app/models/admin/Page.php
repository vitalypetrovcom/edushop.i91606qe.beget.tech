<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;
use wfm\App;
use wfm\Router;

class Page extends AppModel { // Модель (класс) для работы со страницами сайта в админ-панели

    public function get_pages ($lang, $start, $perpage): array { // Метод для получения всех страниц сайта из БД. На вход передаем параметры $lang, $start, $perpage

        return R::getAll ("SELECT p.*, pd.title FROM page p JOIN page_description pd on p.id = pd.page_id WHERE pd.language_id = ? LIMIT $start, $perpage", [$lang['id']]); // Получаем данные о всех страницах сайта из таблиц page и page_description БД по языку pd.language_id и с лимитом вывода на страницу $start, $perpage
    }

    public function deletePage ($id): bool { // Метод для удаления страниц сайта из админ-панели

        // Нам удаление страницы нужно производить из двух таблиц - используем метод транзакции
        R::begin (); // Используем транзакцию RedBeanPHP используя try-catch
        try {

            // Удаляем страницу из таблицы page
            $page = R::load ('page', $id); // Получим по id экземляр объекта page
            if (!$page) { // Если мы не получили данные страницы по id (массив $page пуст)
                return false;
            }
            R::trash ($page); // Для удаления страницы $page из БД используем метод trash

            // Удаляем страницу из таблицы page_description
            R::exec ("DELETE FROM page_description WHERE page_id = ?", [$id]); // Удаляем страницу из таблицы page_description по $id страницы

            R::commit (); // Если все прошло успешно, тогда мы делаем commit этих данных
            return true;

        } catch (\Exception $e) {
            R::rollback (); // Можем откатить транзакцию если у нас что-то пошло не по плану
            return false;
        }
    }

    public function page_validate (): bool { // Метод для валидации данных при создании страницы сайта

        $errors = ''; // Объявляем переменную $errors для сбора ошибок и записываем в нее пустую строку
        // Частные признаки
        foreach ($_POST['page_description'] as $lang_id => $item) { // Выбираем из массива $_POST по ключу 'page_description' данные в формате ключ-данные $lang_id => $item (выбираем по языку $lang_id)
            $item['title'] = trim ($item['title']); // Убираем пробелы вначале и конце строки
            $item['content'] = trim ($item['content']);
            if (empty($item['title'])) { // Если $item['title'] пустой
                $errors .= "Не заполнено Наименование во вкладке {$lang_id}!<br>";
            }
            if (empty($item['content'])) { // Если $item['content'] пустой
                $errors .= "Не заполнено описание во вкладке {$lang_id}!<br>";
            }
        }

        if ($errors) { // Если у нас $errors не пустая
            $_SESSION['errors'] = $errors; // Запишем ошибки в массив сессии
            $_SESSION['form_data'] = $_POST; // В массиве $_SESSION['form_data'] сохраним данные $_POST из заполненной формы
            return false;
        }
        return true; // Если мы прошли проверку валидации, вернем true
    }

    public function save_page (): bool { // Метод для сохранения страницы в БД

        R::begin (); // Используем транзакцию RedBeanPHP используя try-catch
        try {

            $lang = App::$app->getProperty ('language')['id']; // Получаем текущий активный язык из контейнера App
            /*debug ($lang); // Проверка правильности выполнения*/

            // Выгружаем данные в таблицу page
           $page = R::dispense ('page'); // Создаем bean $page
           $page_id = R::store ($page); // Нам нужно получить и сохранить $page_id, потому как он используется для формирования slug, который должен нам вернуть id сохраненной записи

           $page->slug = AppModel::create_slug ('page', 'slug', $_POST['page_description'][$lang]['title'], $page_id);// Теперь, получив id, мы можем в объект $page добавить slug
           R::store ($page); // Повторно сохраняем товар в таблицу page БД потому, что у нас уже появился slug этой страницы

            // Выгружаем данные в таблицу page_description
            foreach ($_POST['page_description'] as $lang_id => $item) { // После этой операции, мы можем пройтись по массиву page_description, чтобы получить нужные данные
                R::exec ("INSERT INTO page_description (page_id, language_id, title, content, keywords, description) VALUES (?,?,?,?,?,?)", [
                    $page_id,
                    $lang_id,
                    $item['title'],
                    $item['content'],
                    $item['keywords'],
                    $item['description'],
                ]); // Выполняем запрос на добавление заполненных данных товара в таблицу page_description БД
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