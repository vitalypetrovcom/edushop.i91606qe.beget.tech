<?php

namespace wfm;

class Pagination { // Класс для обработки пагинации на страницах категорий, товаров, поиска, админки итп.

    public $currentPage; // Номер текущей страницы
    public $perpage; // Сколько выводить товаров на одну страницу
    public $total; // Общее количество товаров
    public $countPages; // Общее количество страниц
    public $uri; // Параметры пользовательского запроса (например, кроме GET параметра page, это могут быть и другие параметры сортировки на странице категорий итд)

    public function __construct($page, $perpage, $total) // Передаем значения, которые важны для построения пагинации: $page (номер текущей страницы), $perpage, $total - нужно понять, сколько нам нужно страниц в постраничной навигации
    {
        $this->perpage = $perpage;
        $this->total = $total;
        $this->countPages = $this->getCountPages(); // Высчитываем количество страниц пагинации (минимум 1 страница)
        $this->currentPage = $this->getCurrentPage($page); // Получаем значение текущей страницы
        $this->uri = $this->getParams(); // Заполняет свойства $uri.
    }

    public function getHtml() // Метод, строит постраничную пагинацию
    {
        $back = null; // ссылка НАЗАД
        $forward = null; // ссылка ВПЕРЕД
        $startpage = null; // ссылка В НАЧАЛО
        $endpage = null; // ссылка В КОНЕЦ
        $page2left = null; // вторая страница слева
        $page1left = null; // первая страница слева
        $page2right = null; // вторая страница справа
        $page1right = null; // первая страница справа

        // $back
        if ($this->currentPage > 1) { // Если текущая страница больше чем 1 (2, 3 итд. страницы), значит ссылка назад $back нам нужна. Если условие не выполняется, тогда мы находимся на 1-ой странице и ссылка назад нам не нужна.
            $back = "<li class='page-item'><a class='page-link' href='" . $this->getLink($this->currentPage - 1) . "'>&lt;</a></li>";
        }

        // $forward
        if ($this->currentPage < $this->countPages) { // Берем текущую страницу "$this->currentPage" если она меньше общего количества страниц "$this->countPages", тогда нам нужна ссылка вперед
            $forward = "<li class='page-item'><a class='page-link' href='" . $this->getLink($this->currentPage + 1) . "'>&gt;</a></li>"; // Берем текущую и прибавляем 1-цу
        }

        // $startpage
        if ($this->currentPage > 3) { // Устанавливает ссылку на 1-ю страницу. Здесь будем выводить несколько страниц слева и справа от текущей. Проверка: Если текущая страница больше 3, тогда нам нужна ссылка на 1-ю страницу (не будут показываться все страницы, а будет троеточие ..., например: 1 ... 3, 4, 5, 6)
            $startpage = "<li class='page-item'><a class='page-link' href='" . $this->getLink(1) . "'>&laquo;</a></li>";
        }

        // $endpage
        if ($this->currentPage < ($this->countPages - 2)) { // Устанавливает ссылку на последнюю страницу. Берем текущую страницу, если она меньше чем общее количество страниц минус 2 страницы, тогда нужна ссылка на последнюю страницу
            $endpage = "<li class='page-item'><a class='page-link' href='" . $this->getLink($this->countPages) . "'>&raquo;</a></li>";
        }

        // $page2left
        if ($this->currentPage - 2 > 0) { // 2 ссылки слева
            $page2left = "<li class='page-item'><a class='page-link' href='" . $this->getLink($this->currentPage - 2) . "'>" . ($this->currentPage - 2) . "</a></li>";
        }

        // $page1left
        if ($this->currentPage - 1 > 0) { // 1 ссылка слева
            $page1left = "<li class='page-item'><a class='page-link' href='" . $this->getLink($this->currentPage - 1) . "'>" . ($this->currentPage - 1) . "</a></li>";
        }

        // $page1right
        if ($this->currentPage + 1 <= $this->countPages) { // 1 ссылка справа
            $page1right = "<li class='page-item'><a class='page-link' href='" . $this->getLink($this->currentPage + 1) . "'>" . ($this->currentPage + 1) . "</a></li>";
        }

        // $page2right
        if ($this->currentPage + 2 <= $this->countPages) { // 2 ссылки справа
            $page2right = "<li class='page-item'><a class='page-link' href='" . $this->getLink($this->currentPage + 2) . "'>" . ($this->currentPage + 2) . "</a></li>";
        }

        return '<nav aria-label="Page navigation example"><ul class="pagination">' . $startpage . $back . $page2left . $page1left . '<li class="page-item active"><a class="page-link">' . $this->currentPage . '</a></li>' . $page1right . $page2right . $forward . $endpage . '</ul></nav>'; // Возвращаем мы строку пагинации, которая у нас есть в верстке, Здесь мы выводим все упомянутые выше ссылки
    }

    public function getLink($page) // Метод, задача которого корректно сформировать ссылку
    {
        if ($page == 1) {
            return rtrim($this->uri, '?&'); // Для первой страницы добавление "page" в uri не нужно. Дополнительно, вырезаем не нужные нам GET параметры '?&'
        }

        if (str_contains($this->uri, '&')) { // Для прочих случаев, в ссылке добавляет "page" в uri страницы. Проверка: "page" единственный параметр или еще есть другие параметры, тогда нам нужно добавить амперсант '&'
            return "{$this->uri}page={$page}";
        } else {
            if (str_contains($this->uri, '?')) { // Если строка запроса содержит '?'
                return "{$this->uri}page={$page}"; // Тогда к текущему uri добавляем "page={$page}"
            } else { // Иначе, к текущему uri добавляем "?page={$page}"
                return "{$this->uri}?page={$page}";
            }
        }
    }

    public function __toString() // Магический метод, который при обращении к экземпляру класса как к строке (мы создадим объект класса "Pagination", при обращении к нему как к строке (через echo) вызывается данный магический метод, который вызовет метод getHtml и вернет нам построенную пагинацию (( return '<nav aria-label="Page navigation example"><ul class="pagination">' . $startpage . $back . $page2left . $page1left . '<li class="page-item active"><a class="page-link">' . $this->currentPage . '</a></li>' . $page1right . $page2right . $forward . $endpage . '</ul></nav>'; ))
    {
        return $this->getHtml();
    }

    public function getCountPages() // Метод, высчитываем количество страниц пагинации и округляем в большую сторону
    {
        return ceil($this->total / $this->perpage) ?: 1;
    }

    public function getCurrentPage($page) // Метод, получаем значение текущей страницы. Передаем на вход номер запрошенной страницы (берем его из GET параметра)
    {
        if (!$page || $page < 1) $page = 1; // Проверка: GET параметр должен быть и быть не меньше 1, ИНАЧЕ в $page присвоим 1
        if ($page > $this->countPages) $page = $this->countPages; // Проверка: если номер запрашиваемой страницы больше значения общего количества страниц, тогда мы в $page = $this-> присвоим общее количество страниц
        return $page;
    }

    public function getStart() // Метод, нужен, чтобы указать в дальнейшем SQL запросе с какого товара нужно начать выборку товара (показывать на странице товары, например 1-3 (стр1), 4-6 (стр2), 7-9 (стр3), если в параметрах отображения стоит 3 товара на странице)
    {
        return ($this->currentPage - 1) * $this->perpage; // Номер текущей страницы "$this->currentPage" отнимаем 1 и умножаем на количество товаров на странице (в SQL запросе будет что-то например: LIMIT 0,3 - начинай выбирать с 0 товара и возьми 3 товара)
    }

    public function getParams() // Метод, заполняет свойства $uri. Нам нужно собрать все GET параметры, кроме "page" (page мы должны исключить из запроса, мы будем его добавлять самостоятельно уже дальше в других методах)
    {
        $url = $_SERVER['REQUEST_URI']; // Берем GET параметры из запроса пользователя
        $url = explode('?', $url); // Разбиваем их по знаку '?', чтобы получить GET параметры (http://new-ishop.loc/category/mac?page=1&sort=name НА http://new-ishop.loc/category/mac И page=1&sort=name)
        $uri = $url[0]; // В $uri мы кладем первый элемент массива (http://new-ishop.loc/category/mac - до знака '?')
        if (isset($url[1]) && $url[1] != '') { // Вторая часть запроса (page=1&sort=name) попадет в массив с индексом "0". Мы проверяем, если существует вторая часть И она не равна пустой строке, тогда мы будем формировать строку запроса
            $uri .= '?'; // Добавляем знак '?'
            $params = explode('&', $url[1]); // Разбиваем оставшиеся параметры по амперсанту '&'
            foreach ($params as $param) { // Проходимся по ним в цикле
                if (!preg_match("#page=#", $param)) $uri .= "{$param}&"; // Проверка, если мы столкнулись с параметром "page" (постраничная навигация), тогда мы его пропустим ... Иначе мы все это добавим в $uri и будем формировать строку запроса (добавлять амперсанты и GET параметры дальше)
            }
        }
        return $uri;
    }


}