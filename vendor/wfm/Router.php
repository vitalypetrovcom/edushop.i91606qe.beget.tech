<?php


namespace wfm;


use Exception;

class Router {

    protected static array $routes = []; // Будет содержаться таблица маршрутов, которую мы будем добавлять в этот массив "routes"
    protected static array $route = []; // Будет попадать конкретный один маршрут (помещен в массив "route"), с которым маршрутизатор нашел соответствие проходя по всей таблице маршрутов, и перенаправит на обработку конкретному контроллеру

    public static function add ($regexp, $route = []) { // Метод для добавления маршрутов в таблицу "routes". "$regexp" - шаблон для регулярного выражения, который будет описывать тот или иной url-адрес, "$route" - соответствие, тот контроллер и тот экшн(метод), который необходимо соотнести с данным шаблонным адресом. Если пользователь не укажет конкретные параметры в url-адресе (контроллер и метод), будут использованы параметры (контроллер и метод) по умолчанию.

        self::$routes[$regexp] = $route; // В таблицу маршрутов, по ключу, который мы получаем аргументом "$regexp" добавляем то, что у нас пришло в "route" (контроллер и метод)
    }

    public static function getRoutes (): array { // Будет возвращать массив, все маршруты "routes"

        return self::$routes;

    }

    public static function getRoute (): array { // Будет возвращать массив, конкретный маршрут "route", с которым было найдено соотвествие

        return self::$route;

    }

    /**
     * Делаем статичные методы, чтобы не создавать экземпляр данного класса "router", а просто вызывать методы из данного класса
     */

    protected static function removeQueryString ($url) { // Служебный метод для отсечения GET параметров (тегов и так далее - "?id=2&page=23") в адресе url (http://new-ishop.loc/page/view?id=2&page=23).

        if ($url) {
            $params = explode ('&', $url, 2); // Разбивка строки запроса "url" на два элемента массива по разделителю '&'
            if (false === str_contains($params[0], '=')) { // str_contains — Определяет, содержит ли строка заданную подстроку
                return rtrim ($params[0], '/'); // Удаляем, при наличии, концевой слеш '/'.

            }

        }
        return '';

    }

    public static function dispatch ($url) { // Метод принимает запросы "url"

        /*var_dump ($url); // Распечатаем то, что получаем в качестве аргумента "$url"*/
        $url = self::removeQueryString ($url); // Вызываем метод для отсечения GET параметров в адресе url
        if (self::matchRoute ($url)) { // Если найдено соответствие с таблицей маршрутов - нам нужно сформировать объект для данного контроллера.

            $controller = 'app\controllers\\' . self::$route['admin_prefix'] . self::$route['controller'] . 'Controller'; // Формирование наименования (пути) контроллера. Все наши контроллеры должны иметь в конце постфикс 'Controller'. Мы будем в работе вызывать только те классы, у которых есть постфикс 'Controller'. (Например для главной страницы - "app\controllers\MainController", для админки - "app\controllers\admin\MainController")

            if (class_exists ($controller)) { // Проверка существования контроллера: если есть - мы создаем экземпляр (объект) данного класса

                /** @var Controller $controllerObject */ // Мы здесь указали, что у нас переменная "$controllerObject" оно является объектом класса "Controller"
                $controllerObject = new $controller(self::$route); // Передаем в объект (в конструктор контроллера) текущий маршрут. Все создаваемые контроллеры будут наследовать данные базового ядра 'Controller' (self::$route)

                $controllerObject->getModel (); // Мы вызвали метод "getModel", который у нас для свойства "model" записал какой-либо объект

                $action = self::lowerCamelCase (self::$route['action'] . 'Action'); // Формирование наименования (пути) метода "action". Все наши методы "action" должны иметь в конце постфикс 'Action'. А если такого постфикса не будет - данный метод будет считаться служебным и вызваться никак не сможет.
                if (method_exists ($controllerObject, $action)) { // Мы должны теперь это проверить с помощью функции method_exists

                    $controllerObject->$action(); // Если метод существует - Вызываем данный метод.
                    $controllerObject->getView (); // Вызываем метод "getView" для вывода вида. В "action" мы можем переопределить вид (если нам нужно, если нет, то по умолчанию у нас отрабатывает следующее соглашение: вид находится в папке с названием контроллера и соответствует названию экшена - если контроллер называется "MainController", тогда папка с видом будет называться "Main", если экшн в этом контроллере называется "index", тогда вид будет называться "index" (файл index.php)

                } else {
                    throw new Exception("Метод {$controller}::{$action} не найден!", 404); // Например "PageController::view" не найден! - Будем знать, что конкретно не найдено: "контроллер" или "метод" или вообще ничего.
                }

            } else {
                throw new Exception("Контроллер {$controller} не найден!", 404); // Выдаем исключение: ошибку 404 и сообщение "Контроллер не найден!"
            }

        } else {
            throw new Exception("Страница не найдена!", 404); // Не найдено соотвествие в таблице маршрутов и мы можем выдавать исключение, которое будет перехвачено нашим обработчиком - вернем ошибку 404 и сообщение "Страница не найдена!"
        }

    }

    public static function matchRoute ($url): bool { // В этом методе мы будем вызывать функцию preg_match и сравнивать поступивший запрос от пользователя с регулярным выражением из таблицы маршрутов нашего маршрутизатора. Этот метод мы будем вызывать в методе "dispatch"

        foreach (self::$routes as $pattern => $route) {


            if (preg_match ("#{$pattern}#", $url, $matches)) { // "#$pattern#" - Шаблон регулярного выражения должен иметь ограничители шаблона (например "#") с тем, чтобы понимать, что после этих ограничителей идет сам шаблон, можно поставить флаг "i" ("#{$pattern}#i") - делает наш шаблон регистронезависимым (по необходимости)
                /*debug ($route); // Для проверки как отрабатываются правила маршрутов
                debug ($matches);*/
                foreach ($matches as $k => $v) { // Выбираем из массива строковые ключи
                    if (is_string ($k)) {
                        $route[$k] = $v;
                    }
                }
                if (empty($route['action'])) { // Проверка на наличие экшн в запросе пользователя
                    $route['action'] = 'index';
                }
                if (!isset($route['admin_prefix'])) { // Проверка на наличие "admin_prefix" в запросе.
                    $route['admin_prefix'] = '';
                } else { // Если он есть - мы к нему в конце добавим "\\" - они нужны для пространства имен (при работе с админкой)
                    $route['admin_prefix'] .= '\\';
                }

                /*debug ($route);*/
                $route['controller'] = self::upperCamelCase ($route['controller']); //  Переводим "new-product" => "NewProduct"
                /*debug ($route);*/
                self::$route = $route;
                return true;

            }
        }

        return false;

    }


/**
 * Именования:
 * У нас есть различные способы именования и рекомендации (в том числе и psr) по именованию классов и методов. Например, когда мы работаем с классами, то классы принято именовать в виде "CamelCase". Когда мы работаем с методами, их принято именовать в виде "camelCase".
 * Тогда: из адреса "new-product" сделать "NewProduct"
 */

    protected static function upperCamelCase ($name): string { // Метод для перевода "new-product" => "NewProduct"
        /*$name = str_replace ('-', ' ', $name); // new-product => new product
        $name = ucwords ($name); // new product => New Product
        $name = str_replace (' ', '', $name); // New Product => NewProduct*/

        return str_replace (' ', '', ucwords (str_replace ('-', ' ', $name))); // В одной строке: new-product => NewProduct

    }

    protected static function lowerCamelCase ($name): string { // Метод будет преобразовывать название "action" в виде "camelCase"
        /*$name = str_replace ('-', ' ', $name); // new-product => new product
        $name = ucwords ($name); // new product => New Product
        $name = str_replace (' ', '', $name); // New Product => NewProduct*/

        return lcfirst (self::upperCamelCase ($name)); // NewProduct => newProduct

    }

}