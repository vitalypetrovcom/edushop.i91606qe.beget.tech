<?php

/**
 * 1. Создаем экземпляр контроллера
 * 2. Определяем вид представления для вывода
 * 3. Определяем метод getView, который подключает нужный нам вид, определить шаблон итд.
 * 4. Проверяем в конструкторе необходимые нам свойства
 * 5. Проверяем, не переопределен ли был шаблон
 * 6. Запускаем метод для отрисовки страницы
 * 7. Извлекаем данные из массива, если они нам были переданы
 * 8. Формируем путь к файлу вида
 * 9. Формируем путь к файлу шаблона
 * 10. Забираем вид из буфера и передаем это в свойство "content"
 * 11. Свойство контент нам доступно в шаблоне страницы
 */


namespace wfm;

use RedBeanPHP\R;

class View { // Класс для подготовки представления вида в браузере

    public string $content = ''; // Данное свойство нужно для создания контентной (изменяемой, в отличие от шаблона) части сайта со всеми переменными, которые мы в нее передаем. Этот контент будет подключаться к существующему шаблону (в нем определены (неизменны): header, footer, sidebars итд.)

    public function __construct (
        public $route, // С текущим маршрутом
        public $layout = '', // С названием шаблона (если мы его сюда передадим)
        public $view = '', // Вид представления
        public $meta = [], // Мета-данные для страницы сайта
    ) { // Будет создавать экземпляр вида

        if ( false !== $this->layout ) { // Значит мы должны как-то определить этот шаблон
            $this->layout = $this->layout ?: LAYOUT; // Либо шаблон, указанный в переменной "layout", либо шаблон по умолчанию - указан в константе "LAYOUT". Переменную "layout" мы всегда можем переопределить через контроллер.
        }

    }

    public function render ( $data ) { // Метод для отрисовки страницы (подключает шаблон (вставляет в него вид) и передает в него контент)

        if ( is_array ( $data ) ) {
            extract ( $data ); // Извлекаем данные (функция берет по ключам данные в массиве) и создает соответствующие переменные (ключи "А" и "В" с данными в массиве превратятся в переменные "А" и "В" с соответствующими данными).
        }
        $prefix = str_replace ( '\\', '/', $this->route[ 'admin_prefix' ] ); // Меняем в адресе запроса обратный слэш на прямой слэш (admin\ => admin/).
        $view_file = APP . "/views/{$prefix}{$this->route['controller']}/{$this->view}.php"; // Формируем путь к нужному файлу

        if ( is_file ( $view_file ) ) { // Если такой вид (файл есть), мы попытаемся его подключить
            ob_start (); // Буферизируем вид представления
            require_once $view_file; // Подключаем файл, содержимое которого попадает в буфер обмена
            $this->content = ob_get_clean (); // Забираем вид из буфера и передаем это в свойство "content" - это свойство нам становится доступно в шаблоне (<?= $this->content; ?\>)

        } else { // В противном случае выдаем исключение
            throw new \Exception( "Не найден вид {$view_file}", 500 );

        }

        if ( false !== $this->layout ) {
            $layout_file = APP . "/views/layouts/{$this->layout}.php"; // Мы определяем путь к шаблону
            if ( is_file ( $layout_file ) ) { // Проверяем если есть такой файл
                require_once $layout_file; // Тогда мы его подключаем
            } else { // В противном случае выдаем исключение
                throw new \Exception( "Не найден шаблон {$layout_file}", 500 );
            }
        }
    }

    public function getMeta () { // Данный метод будет формировать для нас вывод мета - тегов
        $out = '<title>' . App::$app->getProperty ('site_name') . ' | ' . h ( $this->meta[ 'title' ] ) . '</title>' . PHP_EOL;
        $out .= '<meta name="description" content="' . h ( $this->meta[ 'description' ] ) . '">' . PHP_EOL;
        $out .= '<meta name="keywords" content="' . h ( $this->meta[ 'keywords' ] ) . '">' . PHP_EOL;
        return $out;

    }

    public function getDbLogs () { // Собираем логи из БД
        if (DEBUG) {

                $logs = R::getDatabaseAdapter()
                    ->getDatabase()
                    ->getLogger();

                $logs = array_merge ($logs->grep('SELECT'), $logs->grep('select'), $logs->grep('INSERT'), $logs->grep('UPDATE'), $logs->grep('DELETE'));   // Объединяем логи (массивы) всех типов запросов в единый массив. Написание операторов CRUD в запросах РЕГИСТРОЗАВИСИМО (ЗАГЛАВНЫЕ 'SELECT' или прописные буквы 'select' - это разные запросы)!
                debug ($logs);

        }
    }

    public function getPart ($file, $data = null) { // Метод для подключения частей шаблона представления (хедер, футер, сайдбар итд.) по частям. "$file" - файл с частью шаблона, "$data" - дополнительные данные

        if (is_array ($data)) { // Проверим, если у нас $data это массив
            extract ($data); // Мы можем извлечь из него необходимые данные и эти данные будут доступны в подключаемом шаблоне
        }
        $file = APP . "/views/{$file}.php";   // Формируем путь к этому подключаемому шаблону
        if (is_file ($file)) { // Проверка на существование файла
            require $file; // Если файл есть, мы его подключаем
        } else {
            echo "File {$file} not found!";
        }


    }


}