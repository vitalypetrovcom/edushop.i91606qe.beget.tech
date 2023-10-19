<?php


namespace app\models\admin;


use app\models\AppModel;
use RedBeanPHP\R;

class Download extends AppModel { // Модель (класс) для получения данных о цифровых товарах и прикрепляемых к ним файлах из БД

    public function get_downloads($lang, $start, $perpage): array { // Метод для получения списка файлов для цифровых товаров из БД. Принимает на вход $lang, $start, $perpage
        return R::getAll("SELECT d.*, dd.* FROM download d JOIN download_description dd on d.id = dd.download_id WHERE dd.language_id = ? LIMIT $start, $perpage", [$lang['id']]);
    }

}