<?php


namespace app\controllers\admin;

use app\models\admin\Order;
use RedBeanPHP\R;
use wfm\Pagination;

/** @property Order $model */
class OrderController extends AppController { // Контроллер (класс) для работы с заказами в админ-панели

    public function indexAction() { // Метод для отображения заказов в админ-панели
        $status = get('status', 's'); // Получаем статус заказа из массива GET по ключу 'status' в виде строки 's'
        $status = ($status == 'new') ? ' status = 0 ' : ''; // Если статус заказа $status == 'new', тогда мы сразу с формируем подстроку для дальнейшего SQL запроса (' status = 0 ' - мы будем выбирать записи, для которых статус равен 0). В противном случае (если у нас статуса нет или пришел статус не равный 'new') - запишем пустую строку ''

        // Выводим список заказов на страницу используя пагинацию
        $page = get('page'); // Нам потребуется здесь пагинация, поэтому получим GET параметр page для пагинации
        $perpage = 10; // Количество товаров на странице
        $total = R::count('orders', $status); // Нам нужно понять сколько всего у нас заказов в БД (делаем запрос из таблицы 'orders' по условию значения переменной $status - либо ' status = 0 ' либо пустая строка '')
        $pagination = new Pagination($page, $perpage, $total); // Получаем объект пагинации. Передаем на вход нужные параметры $page, $perpage, $total
        $start = $pagination->getStart(); // Переменная $start - с какой позиции нам начинать выбирать записи из БД при выдаче на страницу при пагинации

        $orders = $this->model->get_orders($start, $perpage, $status); // Получаем заказы из БД в переменную $orders. На вход передаем параметры $start, $perpage, $status

        /*debug ($orders); // Распечатаем список заказов из переменной $orders*/

        $title = 'Список заказов'; // Объявляем переменную $title и записываем в нее значение названия страницы
        $this->setMeta("Админка :: {$title}"); // Передаем название title в представление
        $this->set(compact('title', 'orders', 'pagination', 'total')); // Передаем сами данные переменных в вид
    }

}