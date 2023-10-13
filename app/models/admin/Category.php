<?php


namespace app\models\admin;


use app\models\AppModel;
use RedBeanPHP\R;

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



}