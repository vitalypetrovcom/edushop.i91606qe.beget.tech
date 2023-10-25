<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;

class Slider extends AppModel { // Модель (класс) для работы со слайдером в админ панели

    public function get_slides (): array { // Метод для получения слайдов из БД: array

        return R::getAssoc ("SELECT * FROM slider "); // Нам нужен ассоциативный массив - мы получаем его методом getAssoc

    }

    public function update_slider (): void { // Метод для обновления данных о слайдах в БД после редактирования формы в админ-панели

        if (!isset($_POST['gallery'])) { // Если в массиве $_POST по ключу 'gallery' нет данных (в массиве $_POST не существует этого элемента - когда мы удалим все картинки из слайдера)
            R::exec ("DELETE FROM slider"); // Выполняем запрос на удаление всех картинок из таблицы slider в БД
        }
        if (isset($_POST['gallery']) && is_array ($_POST['gallery'])) { // Если в массиве $_POST существует элемент 'gallery' И $_POST['gallery']) - это массив
            $gallery = $this->get_slides (); // Получим все слайды методом get_slides в виде ассоциативного массива
            if ( (count ($gallery) != $_POST['gallery']) || array_diff ($gallery, $_POST['gallery']) || array_diff ($_POST['gallery'], $gallery)) { // Если длина массива, который мы только что достали, не равняется длине массива, которую мы только что получили в массиве $_POST['gallery'] ИЛИ (функция array_diff - сравнит два массива и если мини есть разница, тогда мы должны что-то делать)
                R::exec ("DELETE FROM slider"); // Выполняем запрос на удаление всех картинок из таблицы slider в БД
                $sql = "INSERT INTO slider (img) VALUES "; // Добавляем заново все то (картинки), что к нам пришло от пользователя через форму. Сформируем SQL запрос и в цикле заполним его данными
                foreach ( $_POST[ 'gallery' ] as $item ) { // $item - путь к картинке
                    $sql .= "(?),";
                }
                $sql = rtrim ( $sql, ',' ); // Убираем лишнюю последнюю запятую
                R::exec ( $sql, $_POST[ 'gallery' ] ); // Выполняем SQL запрос. В $_POST['gallery'] у нас будут перечислены пути к картинкам, которые мы подставим в запрос вместо знака вопрос "?"
            }

        }

    }


}