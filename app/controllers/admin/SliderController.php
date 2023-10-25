<?php

namespace app\controllers\admin;

use app\models\admin\Slider;

/** @property Slider $model */

class SliderController extends AppController { // Контроллер (класс) для работы со слайдером в админ-панели

    public function indexAction () { // Метод для отображения слайдера в админ-панели

        // Если данные нам пришли POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Если в массиве $_SERVER по ключу 'REQUEST_METHOD' значение равно 'POST' (значит пользователь гарантированно отправил данные из формы редактирования)
            $this->model->update_slider (); // Обновляем (перезаписываем) данные картинок из отправленной формы в таблице slider в БД
            $_SESSION['success'] = 'Слайдер успешно обновлен!'; // Выдаем сообщение об успехе
            redirect (); // Редирект на ту же страницу
        }

        $gallery = $this->model->get_slides (); // Объявим переменную $gallery и передадим в нее данные о всех слайдах из БД

        // Если данные нам пришли GET
        // Устанавливаем мета-данные для вывода на страницу
        $title = 'Управление слайдером'; // Объявляем переменную $title и записываем в нее значение
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title', 'gallery')); // Передаем сами данные переменных в вид

    }

}