<?php
/**
 * Файл маршрутов.
 *
 * http://new-ishop.loc/page/view/contact  - Задача маршрутизатора разобрать данный запрос пользователя на составляющие: "page" - наименование конкретного контролера "page", "view" - наименование метода контроллера "page", "contact" - параметр, указывает наименование кокретной страницы сайта, куда контроллер должен перенаправить пользователя (показать страницу "contact").
 * http://new-ishop.loc/page/contact - в данном примере "page" - контроллер, далее должен  быть метод по умолчанию, который контроллер должен отработать, затем "contact" - параметр метода, который указывает на то, какую именно страницу показать
 * http://new-ishop.loc - в данном примере, маршрутизатор должен понять, какой должен быть использован контроллер по умолчанию, метод по умолчанию и параметр конкретной страницы по умолчанию.
 *
 * Для создания Маршрутизатора, нам нужна Таблица маршрутов - это массив адресов-маршрутов, которые представляют из себя регулярные выражения, описывающие группы страниц. Для каждого из них, будет вызываться конкретный контроллер и метод (экшн).
 */

use wfm\Router; // Подключаем пространство имен "Router"

/**
 * Добавление правил маршрутов с помощью регулярных выражений:
 */

/** ВНИМАНИЕ!
 * page about contact (page)[a-z]+ - В регулярном выражении более конкретная часть "(page)" располагается перед(раньше) более общей частью "[a-z]+", чтобы при переборе массива через оператор foreach в первую очередь был поиск соотвествия с наиболее конкретной частью (уникальной), а уже затем с более общей частью.
 */

/**
 * Правила для админки по умолчанию (первичны):
 */

Router::add ('^admin/?$', ['controller' => 'Main', 'action' => 'index', 'admin_prefix' => 'admin']); // Маршрут для главной страницы админки.

Router::add ('^admin/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$', ['admin_prefix' => 'admin']); // Маршрут для прочих страниц админки.

/**
 * Правила для клиентской части сайта по умолчанию (default) (вторичны):
 */
Router::add ('^$', ['controller' => 'Main', 'action' => 'index']); // Маршрут(правило) для главной страницы. "action" по умолчанию всегда у нас будет "index"

Router::add ('^(?P<controller>[a-z-]+)/(?P<action>[a-z-]+)/?$'); // (?P<controller>[a-z-]+) - означает, что будет присутствовать набор символов, которые мы запишем с именоваными ключами "<controller>" и "<action>" соотвественно (мы создаем данные ключи в массиве при выполнении функции preg_match). Те, если у нас url - http://new-ishop.loc/page/view - то "page" у нас попадет в первый карман (выражение в скобках) - "(?P<controller>[a-z-]+)", а "view" попадет у нас во второй карман "(?P<action>[a-z-]+)/?$')".









