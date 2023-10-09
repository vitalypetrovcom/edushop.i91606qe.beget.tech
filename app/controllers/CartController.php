<?php

namespace app\controllers;

use app\models\Cart;
use app\models\Order;
use app\models\User;
use wfm\App;

/** @property Cart $model */ // Объявляем модель
class CartController extends AppController { // Контроллер для работы с корзиной

    public function addAction (): bool { // Метод для добавления товаров в корзину

        $lang = App::$app->getProperty ('language'); // Берем язык из контейнера "App::$app->getProperty" по ключу 'language'
        $id = get ('id'); // Получаем данные по ключу 'id' используя функцию "get"
        $qty = get ('qty'); //  - 'qty' -

        if (!$id) { // Проверка: если в $id будет что-то не являющееся числом, тогда дальнейшая работа бессмысленна - мы вернем false
            return false;
        }

        $product = $this->model->get_product ($id, $lang); // Получаем данные о продукте. Вызываем метод в контроллере
//        debug ($product, 1);  // Проверка, получили ли мы продукт (есть ли вообще такой продукт, который запросил пользователь)
        if (!$product) { // Проверка: если в $product будет пустой массив - мы вернем false
            return false;
        }

        $this->model->add_to_cart ($product, $qty); // Обращаемся к модели model и добавляем товар в корзину

        if ($this->isAjax ()) { // Проверяем, отправлялся ли запрос методом Ajax или нет? тогда мы должны подключить какой-либо вид без шаблона, и у нас этот вид вернется в ответ на Ajax запрос
             /*debug ($_SESSION['cart'], 1);*/
            $this->loadView ('cart_modal'); // Подключаем вид методом loadView с названием 'cart_modal' через require. Тк контроллер называется Cart, то данный вид будет искаться в папке views/Cart. Это будет ответ на Ajax запрос

        }
        redirect (); // Иначе, выполняем редирект и пользователь будет возвращен на ту же страницу, с которой он к нам пришел
        return true;

    }

    public function showAction () { // Метод для выполнения 'cart/show' в main.js - отображения товаров в корзине в модальном окне
        $this->loadView ('cart_modal'); // Подключаем вид методом loadView с названием 'cart_modal' через require. Тк контроллер называется Cart, то данный вид будет искаться в папке views/Cart. Это будет ответ на Ajax запрос
    }

    public function deleteAction () { // Метод удаления товара из корзины в модальном окне
        $id = get ( 'id' ); // Принимаем "$id" товара, который передается методом "get"
        if ( isset( $_SESSION[ 'cart' ][ $id ] )) { // Проверка на наличие "id" товара в глобальном массиве "$_SESSION" по ключу "['cart'][$id]"
            $this->model->delete_item ($id); // Удаляем товар методом "delete_item"
        }
        if ( $this->isAjax () ) { // Проверяем, отправлялся ли запрос методом Ajax или нет?
            /*debug ($_SESSION['cart'], 1);*/
            $this->loadView ( 'cart_modal' ); // Подключаем вид методом loadView с названием 'cart_modal' через require.
        }
        redirect (); // Выполняем redirect если запрос не был выполнен методом Ajax
    }

    public function clearAction () { // Метод удаления всех товаров из корзины (очищение корзины) в модальном окне
        if (empty($_SESSION['cart'])) { // Проверка, если у нас пусто в "$_SESSION['cart'])" (наша корзина пуста), тогда
            return false;
        }
        unset($_SESSION['cart']);  // Иначе, мы должны очистить корзину: очищаем "$_SESSION['cart'])" - удаляем товары
        unset($_SESSION['cart.qty']);  // Иначе, мы должны очистить корзину: очищаем "$_SESSION['cart.qty'])" - итоговое количество
        unset($_SESSION['cart.sum']);  // Иначе, мы должны очистить корзину: очищаем "$_SESSION['cart.sum'])" - итоговую сумму
        $this->loadView ( 'cart_modal' ); // Подключаем вид методом loadView с названием 'cart_modal' через require.
        return true;
    }

    public function viewAction () { // Метод для подготовки страницы оформления заказа пользователем

        $this->setMeta (___ ('tpl_cart_title')); // Отправка мета-данных на страницу

    }

    public function checkoutAction () { // Метод для отправки формы заказа на выполнение

        if (!empty($_POST)) { // Если не пуст массив $_POST (пользователь отправил форму заказа)
            // 1. Зарегистрировать пользователя, если он не авторизован (не зарегистрирован)
            if (!User::checkAuth ()) { // Если он не авторизован
                $user = new User(); // Создадим объект $user на основе модели User

                $user->load () ; // Загрузим их в модель из массива $_POST
                if (!$user->validate ($user->attributes) || !$user->checkUnique () ) { // Проверяем данные пользователя на валидацию И email на уникальность

                    $user->getErrors (); // Если не прошли валидацию (есть ошибки). Мы должны их показать используя модель и метод getErrors (запишет ошибки валидации в сессию)

                    $_SESSION['form_data'] = $user->attributes; // Создаем элемент сессии с ключом 'form-data' для хранения вводимых пользователем данных в форму и запишем туда данные из массива $user->attributes
                    redirect (); // Выполняем редирект, чтобы дальнейший код не выполнялся

                } else { // Если валидация и проверка на уникальность прошли успешно

                    $user->attributes['password'] = password_hash ($user->attributes['password'], PASSWORD_DEFAULT); // Перед сохранением пароля из пользовательской формы $user->attributes['password'] в БД, мы хешируем пароль функцией password_hash алгоритмом PASSWORD_DEFAULT
                    // id пользователя нам нужен, чтобы при сохранении заказа понимать какой конкретно пользователь сделал конкретный заказ
                    if (!$user_id = $user->save ('user')) { // Если мы не сохранили пользователя (мы не получили id пользователя !$user_id), значит какая-либо ошибка возникла при сохранении данных пользователя
                        $_SESSION['errors'] = ___ ('cart_checkout_error_register'); // В сессию с ключом 'errors' записываем ошибку регистрации пользователя
                        redirect (); // Выполняем редирект, чтобы дальнейший код не выполнялся
                    }
                }
            }

            // 2. Сохранить заказ в БД
            $data['user_id'] = $user_id ?? $_SESSION['user']['id']; // Поместим в массив $data по ключу 'user_id' значение $user_id (если пользователь не был авторизован) или значение $_SESSION['user']['id'] массива сессии (если пользователь был авторизован на сайте)
            $data['note'] = post ('note'); // У нас есть примечания, которые не попали в аттрибуты модели $attributes и мы его заберем из массива $_POST методом post
            $user_email = $_SESSION['user']['email'] ?? post ('email');  // Создаем переменную $user_email и запишем в нее значение из массива $_SESSION['user']['email'] (если пользователь авторизован) или из массива $_POST (если пользователь не авторизован и мы его зарегистрировали при оформлении заказа на сайте)

            if (!$order_id = Order::saveOrder ($data)) { // Пробуем вызвать метод saveOrder модели Oder. Возвращать будет номер заказа или false. Если у нас нет номера заказа:
                $_SESSION['errors'] = ___ ('cart_checkout_error_save_order'); // Записываем в сессию сообщение об ошибке при оформлении заказа
            } else { // Если у нас есть номер заказа

                // 1. Будем отправлять письма
                Order::mailOrder ($order_id, $user_email, 'mail_order_user'); // Отправляем сообщение пользователю
                Order::mailOrder ($order_id, App::$app->getProperty ('admin_email'), 'mail_order_admin'); // Отправляем сообщение администратору

                // 2. Будем очищать сессию корзины
                unset($_SESSION['cart']); // Удаляем данные из сессии
                unset($_SESSION['cart.sum']);
                unset($_SESSION['cart.qty']);

                $_SESSION['success'] = ___ ('cart_checkout_order_success'); // Записываем в сессию сообщение об успехе при оформлении заказа
            }

        }
        redirect ();


    }



}