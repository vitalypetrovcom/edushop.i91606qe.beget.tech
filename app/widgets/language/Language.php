<?php

namespace app\widgets\language;

use RedBeanPHP\R;

class Language { // Виджет для переключения языков

    protected $tpl; // В нем будет храниться шаблон (html-код данного виджета), который будет реализовывать данный виджет для языков
    protected $languages; // В нем мы будем хранить все имеющиеся языки
    protected $language; // В нем мы будем хранить текущий язык (активный язык, выбранный текущим пользователем на сайте)

    public function __construct () { // Если мы захотим в будущем переопределить шаблон

        $this->tpl = __DIR__ . 'lang_tpl.php'; // Прописываем путь к файлу шаблона
        $this->run(); // При вызове виджета из нужной части представления, вызывать метод "run"
    }

    protected function run () { // Данный метод будет получать "languages" и "language"


    }

    public static function getLanguages (): array { // Метод будет получать список языков на сайте

        return R::getAssoc ("SELECT code, title, base, id FROM language ORDER BY base DESC"); // Метод возвращает ассоциативный массив. !-->> В качестве ключей массива будут поля, которые переданы первыми !!!

    }

    public static function getLanguage ($languages): array { // Метод будет получать активный текущий язык на сайте

    }

}