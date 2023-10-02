<?php


namespace app\widgets\page;


use RedBeanPHP\R;
use wfm\App;
use wfm\Cache;

class Page
{

    protected $language; // Свойство, язык
    protected string $container = 'ul'; // Свойство, контейнер для меню
    protected string $class = 'page-menu'; // Свойство, класс page-menu
    protected int $cache = 3600; // Свойство, кэш - время кэширования по умолчанию (3600 сек)
    protected string $cacheKey = 'ishop_page_menu'; // Свойство, ключ кэшированных данных, по которым можно класть данные в кэш и доставать из кэша
    protected string $menuPageHtml; // Свойство, готовый сформированный Html код меню
    protected string $prepend = ''; // Свойство, возможные данные, которые мы хотим добавить перед меню
    protected $data; // Свойство, это данные, которые мы будем доставать из БД

    public function __construct($options = []) // Метод, создаем экземпляр класса и передаем в него какие-либо опции
    {
        $this->language = App::$app->getProperty('language'); // Получаем информацию об активном языке
        $this->getOptions($options); // Вызываем метод getOptions, который соберет опции, передаваемые при создании объекта и заполнит все свойства класса ($language, $container, $class итд.) передаваемыми значениями опций
        $this->run(); // Метод запускает построение виджета
    }

    protected function getOptions($options) // Метод getOptions, который соберет опции, передаваемые при создании объекта и присвоит значения переданных опций в соответствующие свойства класса ($language, $container, $class итд.)
    {
        foreach ($options as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    protected function run() // Метод запускает построение виджета
    {
        $cache = Cache::getInstance(); // Создаем объект класса Cache
        $this->menuPageHtml = $cache->get("{$this->cacheKey}_{$this->language['code']}"); // Мы пытаемся забрать по ключу данные из кэша

        if (!$this->menuPageHtml) { // Если мы не забрали данные из кэша (их нет в кэше)
            $this->data = R::getAssoc("SELECT p.*, pd.* FROM page p 
                        JOIN page_description pd
                        ON p.id = pd.page_id
                        WHERE pd.language_id = ?", [$this->language['id']]); // Мы обращаемся к БД и пытаемся получить их из БД
            $this->menuPageHtml = $this->getMenuPageHtml(); // Из полученных из БД данных строим меню
            if ($this->cache) { // Если у нас кэширование включено,
                $cache->set("{$this->cacheKey}_{$this->language['code']}", $this->menuPageHtml, $this->cache); // Мы кладем сформированное в виде html кода меню в кэш
            }
        }

        $this->output(); // Вызываем метод output,
    }

    protected function getMenuPageHtml() // Метод, из полученных из БД данных строит меню в виде html кода
    {
        $html = '';
        foreach ($this->data as $k => $v) {
            $html .= "<li><a href='page/{$v['slug']}'>{$v['title']}</a></li>"; // Формируем html код с формированием url адреса и названия страницы
        }
        return $html; // Возвращаем сформированный html код
    }

    protected function output() // Метод output
    {
        echo "<{$this->container} class='{$this->class}'>"; // Оборачивает полученные данные в контейнер с использованием класса, который мы передадим или который будет использован по умолчанию
        echo $this->prepend; // Какой-то код можно поместить перед этим кодом (данными)
        echo $this->menuPageHtml; // Вставляем полученные данные
        echo "</{$this->container}>"; // Закрываем контейнер
    }

}