<?php


namespace wfm;


abstract class Controller { // Создаем абстрактный класс "Controller". От него нельзя будет создать объекты. Он будет содержать в себе все типовые свойства и методы, которые нам могут понадобиться при работе нашего фреймворка. От него мы будем создавать классы-наследники, чтобы использовать готовый код и, при необходимости, дополнять его свойства и методы под реализацию конкретных задач. Он будет наследоваться всеми нашими контроллерами фреймворка. В нем мы будем выполнять все типовые действия, которые при наследовании этого базового контроллера контроллерами приложения в них будут выполняться автоматически.

    public array $data = []; // Массив с данными из модели (из БД), которые мы будем передавать в вид для отображения в браузере
    public array $meta = ['title' => '', 'keywords' => '', 'description' => '']; // Массив, нужен для того, чтобы мы могли из контроллера в шаблон передавать метаданные страницы. По умолчанию, задаем пустые ключи.
    public false|string $layout = ''; // Свойство, в котором мы будем хранить шаблон (мы его определили в файле "config/init.php": "define ("LAYOUT", 'ishop')". При необходимости, мы можем переопределять шаблон, тк разные страницы могут использовать разные шаблоны. Здесь может быть как строка, так и "false" (например, если мы используем Ajax запрос, и нужно показать только вид без подключения шаблона (простой внешний вид без лишней верстки)) - в случае "false" шаблон не будет подключаться
    public string $view = ''; // Мы через контроллер можем переопределить вид (по умолчанию он будет соответствовать названию экшна ("action") и будет находиться в папке с названием контроллера и будет подключаться другой вид.
    public object $model; // Это будет объект модели, это свойство мы тоже будем использовать в контроллере через "This model" мы можем обратиться к той модели (если она есть), которая будет автоматически загружаться для данного контроллера. Так же мы можем самостоятельно создать какие-либо объекты моделей и использовать их в контроллерах.

    public function __construct (public $route = []) {



    }

    public function getModel () { // Мы получим модель, если таковая создана (например, для контроллера "Main" есть соответствующая модель "Main"
        $model = 'app\models\\' . $this->route['admin_prefix'] . $this->route['controller'];
        if (class_exists ($model)) { // Если класс "model" существует - тогда мы в него запишем новый экземпляр модели
            $this->model = new $model();
        }

    }

    public function getView () { // Формируем вид для отображения в браузере
        $this->view = $this->view ?: $this->route['action']; // Проверяем, не переопределили ли мы свойство "view" - если переопределили, то используем его, если нет - тогда запишем в него данные из свойства "route" по ключу "action" (название вида берется из названия экшна).

        (new View($this->route, $this->layout, $this->view, $this->meta))->render ($this->data); // Формируем экземпляр вида класса "View", передаем в него данные для представления (маршрут "route", шаблон "layout", вид "view" и мета данные "meta") и вызываем метод "render" - для вывода представления, в который передается массив с данными "data"

    }

    public function set ($data) { // Метод, чтобы складывать данные в массив "$data" из БД и других источников
        $this->data = $data;
    }

    public function setMeta ($title = '', $description = '', $keywords = '') { // Метод, чтобы записать в "meta" соответствующие переменные (данные)
        $this->meta = [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
        ];
    }


}