<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;
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



}