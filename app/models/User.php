<?php

namespace app\models;

use RedBeanPHP\R;

class User extends AppModel { // Модель (класс) для работы с данными пользователей из БД

    public array $attributes = [ // Здесь укажем требуемые аттрибуты модели, которые нам нужны от пользователя при заполнении им формы регистрации
        'email' => '',
        'password' => '',
        'name' => '',
        'address' => '',
    ];

    public array $rules = [ // Здесь мы укажем массив с правилами
        'required' => ['email', 'password', 'name', 'address',], // Правило для обязательных полей (указываем их в массиве)
        'email' => ['email',], // Правило для проверки 'email' (должно соответствовать email адресу)
        'lengthMin' => [ // Правило для минимальной длины строки - указываем какие поля сколько должны занимать символов
            ['password', 6],
        ],

    ];

    public array $labels = [
        'email' => 'tpl_signup_email_input',
        'password' => 'tpl_signup_password_input',
        'name' => 'tpl_signup_name_input',
        'address' => 'tpl_signup_address_input',
    ]; // Создаем свойство $labels, на выходе которого будет массив с переводными данными по каждому полю

    public static function checkAuth (): bool { // Метод проверки аутентификации пользователя на сайте

        return isset($_SESSION['user']); // Вернем значение из массива $_SESSION по ключу 'user'

    }

    public function checkUnique ($text_error = ''): bool { // Метод проверки на уникальность вводимого email в пользовательской форме. Принимает на вход параметр $text_error = '' (можем передать какой-либо аргумент для ошибки)

        $user = R::findOne ('user', 'email = ?', [$this->attributes['email']]);  // Для того чтобы проверить есть ли в БД уже такой email, используем RedBeanPHP метод findOne (в таблице 'user' ищем 'email = ?' со значением $this->attributes['email'] )
        if ($user) { // Если мы что-то получаем в массиве $user
            $this->errors['unique'][] = $text_error ? : ___ ('user_signup_error_email_unique');   // Записываем сообщение об ошибке 'Этот email уже зарегистрирован'. Сообщения об ошибках у нас хранятся в многомерном массиве errors с созданным нами ключом 'unique'.
            return false;
        }
        return true; // Если массив пуст (такого email в БД нет)
    }

    public function login ($is_admin = false): bool { // Метод модели для авторизации пользователей и администратора. На вход передаем признак роли авторизуемого (админ или пользователь). По умолчанию =  false

        $email = post ('email'); // Забираем данные пользователя из массива $_POST после отправки формы на сайте с помощью функции post (строка)
        $password = post ('password'); // (строка)
        if ($email && $password) { // Проверим, если у нас есть $email и $password
            if ($is_admin) { // Проверяем, если у нас это администратор $is_admin
                $user = R::findOne ('user', "email = ? AND role = 'admin'", [$email]); // Выполняем запрос используя RedBeanPHP метод findOne: "email = ? AND role = 'admin'" - оба условия должны быть выполнены
            } else { // Если обычный пользователь
                $user = R::findOne ('user', "email = ?", [$email]); // Роль "role =" проверять не нужно
            }
            // В результате, мы должны получить данные существующего 'user' из БД или пустой массив (если такого пользователя нет в БД)
            if ($user) { // Проверим, если мы получили данные существующего пользователя из БД
                if (password_verify ($password, $user->password)) { // Мы должны проверить правильность пароля (в виде хеша) используя функцию password_verify. Передаем на вход $password и свойство $user->password (нам достанет метод findOne из БД)

                    foreach ($user as $k => $v) { // Если пароль проверен успешно, тогда мы должны в сессию поместить все данные пользователя (email, name итд с соответствущими значениями)
                        if (!$k != 'password') { // Пароль в сессию добавлять не будем
                            $_SESSION['user'][$k] = $v;
                        }
                    }
                    return true;
                }
            }

        }
        return false;
    }

    public function get_count_orders ($user_id): int { // Метод для подсчета общего количества заказов. На вход передаем $user_id

        return R::count ('orders', 'user_id = ?', [$user_id]); // Получаем общее количество заказов из БД 'orders' по конкретному user_id

    }

    public function get_user_orders ($start, $perpage, $user_id): array { // Метод для получения заказов конкретного пользователя. На вход передаем параметры: начальная запись для выборки $start, количество на странице $perpage, id пользователя $user_id. Возвращаем массив - пустой (нет заказов пользователя) или заполненный данными (есть заказы пользователя)

        return R::getAll ("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT $start, $perpage", [$user_id]);

    }

    public function get_user_order ($id): array { // Метод получения данных о конкретном заказе пользователя. На вход принимается номер заказа пользователя $id. Мы вернем либо заказ (массив с данными заказа) или пустой массив (такого номера заказа нет)

        return R::getAll ("SELECT o.*, op.* FROM orders o JOIN order_product op on o.id = op.order_id WHERE o.id = ?", [$id]);

    }





}