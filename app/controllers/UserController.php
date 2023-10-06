<?php

namespace app\controllers;

use app\models\User;
use wfm\App;
use wfm\Pagination;

/** @property User $model */

class UserController extends AppController { // Контроллер (класс) для работы с пользователями

    public function signupAction () { // Метод для регистрации пользователей


        if (User::checkAuth ()) { // Проверяем, авторизован ли пользователь
            redirect (base_url ()); // Если пользователь авторизован, делаем редирект на главную страницу
        }

        if (!empty($_POST)) { // Проверяем, передали ли мы методом POST данные на сервер. Если массив $_POST не пуст, мы будем работать с переданными данными (передали данные или как минимум, нажали кнопку "отправить")

            $data = $_POST;  // Заберем данные из массива $_POST
            $this->model->load ($data);  // Загружаем данные в модель User методом load
            /*debug ($data);
            debug ($this->model->attributes); // Проверка правильности выполнения*/

            if (!$this->model->validate ($data) || !$this->model->checkUnique ()) { // Проверим, что вернул у нас метод validate (bool) и метод на проверку уникальности email checkUnique
                $this->model->getErrors (); // Если не прошли валидацию (есть ошибки). Мы должны их показать используя модель и метод getErrors (запишет ошибки валидации в сессию)

                $_SESSION['form_data'] = $data; // Создаем элемент сессии с ключом 'form-data' для хранения вводимых пользователем данных в форму и запишем туда данные из массива $data

            } else { // Иначе

                $this->model->attributes['password'] = password_hash ($this->model->attributes['password'], PASSWORD_DEFAULT); // Перед сохранением пароля из пользовательской формы $this->model->attributes['password'] в БД, мы хешируем пароль функцией password_hash алгоритмом PASSWORD_DEFAULT
                if ($this->model->save ('user')) { // Если мы успешно записали данные заполненной пользовательской формы в БД
                    $_SESSION['success'] = ___ ('user_signup_success_register'); // Если прошли валидацию (нет ошибок). Помещаем в массив $_SESSION по ключу 'success' строку сообщения 'Учетная запись была создана'
                } else {
                    $_SESSION['errors'] = ___ ('user_signup_error_register'); // Если не прошли валидацию выдаем ошибку. Помещаем в массив $_SESSION по ключу 'errors' строку сообщения 'Ошибка регистрации'
                }



            }
            redirect (); // Делаем редирект на эту же страницу чтобы не было повторной отправки формы
        }


        $this->setMeta (___ ('tpl_signup')); // Проставим мета-данные страницы

    }

    public function loginAction () { // Метод для авторизации (входа) пользователей на сайте

        if (User::checkAuth ()) { // Проверяем, авторизован ли пользователь
            redirect (base_url ()); // Если пользователь авторизован, делаем редирект на главную страницу
        }

        if (!empty($_POST)) { // Проверим, если у нас массив $_POST не пуст (пользователь отправил данные через форму на сайте)

            if ($this->model->login ()) { // Вызываем метод модели для авторизации пользователей. Если пользователь успешно авторизовался
                $_SESSION['success'] = ___ ('user_login_success_login'); // Запишем сообщение об успехе в сессию с ключом 'success'
                redirect (base_url ()); // Делаем редирект на главную страницу
            } else { // Если не авторизовался (что-то пошло не так - логин/пароль)
                $_SESSION['errors'] = ___ ('user_login_error_login'); // Запишем сообщение об ошибке в сессию с ключом 'errors'
                redirect (); // Делаем редирект на эту же страницу
            }

        }

        $this->setMeta (___ ('tpl_login')); // Устанавливаем мета-данные страницы

    }

    public function logoutAction () { // Метод выхода авторизованного пользователя из личного кабинета

        if (User::checkAuth ()) { // Проверяем, авторизован ли пользователь
            unset($_SESSION['user']); // Если пользователь авторизован мы уберем сведения о его авторизации из сессии
        }
        redirect (base_url () . 'user/login'); // Делаем редирект на страницу авторизации

    }

    public function cabinetAction () { // Метод для работы с личным кабинетом
        if (!User::checkAuth ()) { // Проверяем, авторизован ли пользователь
            redirect (base_url () . 'user/login'); // Делаем редирект на страницу авторизации
        }

        $this->setMeta (___ ('tpl_cabinet')); // Устанавливаем мета-данные страницы
    }

    public function ordersAction () { // Метод для работы со страницей заказов

        if (!User::checkAuth ()) { // Проверяем, авторизован ли пользователь
            redirect (base_url () . 'user/login'); // Делаем редирект на страницу авторизации
        }

        $page = get ('page'); // Для работы с пагинацией объявляем переменную $page (номер страницы)
//        $perpage = App::$app->getProperty ('pagination'); // Количество заказов на странице
        $perpage = 5;
        $total = $this->model->get_count_orders ($_SESSION['user']['id']); // Общее количество заказов для конкретного пользователя берем используя метод модели User get_count_orders и передавая в него id конкретного пользователя $_SESSION['user']['id']
        $pagination = new Pagination($page, $perpage, $total); // Устанавливаем пагинацию на странице заказов
         $start = $pagination->getStart (); // Нужно указать, с какой записи в таблице заказов в БД мы должны получать записи

         $orders = $this->model->get_user_orders ($start, $perpage, $_SESSION['user']['id']); // Получим заказы конкретного пользователя используя метод модели User get_user_orders и передавая в него начальную позицию выборки $start, количество заказов на странице $perpage, id конкретного пользователя $_SESSION['user']['id']

         $this->setMeta (___ ('user_orders_title')); // Передадим мета-данные на страницу заказов
        $this->set (compact ('orders', 'pagination', 'total')); // Передаем сами данные

    }

    public function orderAction () { // Метод для работы со страницей конкретного заказа

        if (!User::checkAuth ()) { // Проверяем, авторизован ли пользователь
            redirect (base_url () . 'user/login'); // Делаем редирект на страницу авторизации
        }

        $id = get ('id'); // Получаем номер заказа
        $order = $this->model->get_user_order ($id);  // В переменную   получаем данные заказа
        if (!$order) { // Если получили пустой массив
            throw new \Exception('Not found order!', 404);
        }

        $this->setMeta (___ ('user_order_title'));  // Если мы получили массив с данными заказа, тогда передаем на страницу мета-данные
        $this->set (compact ('order')); // Передаем сами данные заказа

    }

    public function filesAction () { // Метод для работы со страницей файлов для скачивания цифровых продуктов

        if (!User::checkAuth ()) { // Проверяем, авторизован ли пользователь
            redirect (base_url () . 'user/login'); // Делаем редирект на страницу авторизации
        }

        $lang = App::$app->getProperty ('language'); // Получаем текущий активный язык сайта из контейнера App
        $page = get ('page'); // Нам нужна пагинация на странице. Берем текущую страницу из массива $_GET
        $perpage = App::$app->getProperty ('pagination'); // Количество отображаемых файлов (записей) на странице
//        $perpage = 1;

        $total = $this->model->get_count_files () ; // Общее количество файлов для скачивания
        $pagination = new Pagination($page, $perpage, $total); // Устанавливаем пагинацию на странице файлов
        $start = $pagination->getStart (); // Нужно указать, с какого файла (записи в таблице файлов) в БД мы должны получать записи

        $files = $this->model->get_user_files ($start, $perpage, $lang); // Получаем сами файлы (ссылки на скачивание) в виде массива
        $this->setMeta (___ ('user_files_title')); // Передадим мета-данные
        $this->set (compact ('files', 'pagination', 'total')); // Передаем сами данные файлов: массив файлов 'files', объект пагинации 'pagination', общее количество файлов 'total'


    }





}