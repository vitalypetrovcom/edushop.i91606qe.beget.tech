<?php

if ( PHP_MAJOR_VERSION < 8 ) {
    die('Необходима версия PHP >= 8');
}

require_once dirname ( __DIR__ ) . '/config/init.php';
require_once HELPERS . '/functions.php';
require_once CONFIG . '/routes.php';

new \wfm\App();


//echo \wfm\App::$app->getProperty ('pagination');
//\wfm\App::$app->setProperty ('test', 'TEST');
//var_dump (\wfm\App::$app->getProperties ());



/**
 * Как работает цепочка файлов (index.php -> init.php -> TSingleton.php -> Registry.php -> App.php -> params.php):
 * 1. Подключаем конфигурацию во фронтконтроллере "require_once dirname ( __DIR__ ) . '/config/init.php';" и создаем экземпляр класса "Арр" - "new \wfm\App();"
 * 2. В классе "Арр" есть публичное статичное свойство "арр" (мы всегда можем обратиться записью "\wfm\App::$app" к нему).
 * Что происходит в конструкторе "__construct" при создании экземпляра класса "арр": в контейнер записываются данные реестра (self::$app = Registry::getInstance ();). Для этого, обращаемся к классу "Registry", он реализует паттерн "TSingleton", те экземпляр данного класса мы можем получить только через метод "getInstance". Поэтому здесь мы вызываем метод "getInstance" (self::$app = Registry::getInstance ();). В реестре у нас реализуются два ключевых метода: "setProperty" и "getProperty". Метод "getProperties" нужен только чтобы распечатать все данные из нашего контейнера.
 * 3. В классе "Арр" мы еще вызываем метод "getParams", который берет параметры конфигурации нашего приложения (находятся в "params.php") и складывает их в контейнер. Благодаря этому, в контейнере есть какие-либо данные - мы можем получить оттуда как кокретные данные (echo \wfm\App::$app->getProperty ('pagination');) так и все данные (var_dump (\wfm\App::$app->getProperties ());), а так же что-то дописать в этот контейнер используя метод "setProperty" (\wfm\App::$app->setProperty ('test', 'TEST');).
 *
 * Так же часто во фреймворках практикуют разделение параметров на отдельные файлы (если у нас боьшой фреймворк и у нас много различных конфигураций, настроек. В этом случае, нам нужно "пробежаться" по всем файлам в папке "config" или собрать в массив данные из этих файлов, затем через "foreach ( $params as $k => $v )" пройти по всему массиву и записать эти данные в наш контейнер.
 */

//throw new Exception('Возникла ошибка', 404); // Выдаем исключение для проверки на вывод ошибки (можем передать код ошибки например code = 404)

//echo $test;

//echo 'Hello!';

//debug (\wfm\Router::getRoutes ());


















