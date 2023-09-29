<?php

namespace app\models;

use RedBeanPHP\R;

class Wishlist extends AppModel { // Модель для получения данных из БД для товаров в избранном

    public function get_product ($id): array|null|string { // Метод для получения данных товаров в избранном. Нам нужно будет получить одну ячейку - в куки мы будем хранить не всю информацию о товаре, а только id-шники товаров и только в том случае, если мы достали такой товар по $id. Метод get_product должен вернуть нам массив с id-шником

        return R::getCell ("SELECT id FROM product WHERE status = 1 AND id = ?", [$id]);
    }

    public function add_to_wishlist ($id) { // Метод для добавления товара в куки. Принимает аргументом $id товара, который нужно добавить

        $wishlist = self::get_wishlist_ids ();  // Вызовем метод get_wishlist_ids - для получения либо пустого массива, либо массива id-шников
        if (!$wishlist) { // Проверим, если массив $wishlist пустой (добавляем товар первый раз), тогда
            setcookie ('wishlist', $id, time() + 3600*24*7*4, '/'); // Тогда мы добавляем выбранный товар в куки: куки называются 'wishlist', добавляем $id, время хранения куки (текущая метка времени + 30 дней), хранить мы будем для всего домена '/'
        } else { // Иначе, если не пустой, проверяем: 1. Сколько уже товара в куках, 2. Есть ли такой товар уже в куках, и пользователь пытается добавить его повторно
            if (!in_array ($id, $wishlist)) { // Если нет такого товара в куках, тогда мы добавляем товар в куки
                if (count ($wishlist) > 5) { // Проверяем, сколько товаров уже в куках: если больше 5 - тогда удаляем первый элемент (товар) массива
                    array_shift ($wishlist);
                }
                $wishlist[] = $id; // Добавим новый товар в массив
                $wishlist = implode (',', $wishlist);  // Преобразуем массив в строку
                setcookie ('wishlist', $wishlist, time() + 3600*24*7*4, '/'); // Сохраняем строку в куки
            }

        }

    }

    public static function get_wishlist_ids (): array { // Метод проверки нахождения интересующего нас товара уже в избранном

        $wishlist = $_COOKIE['wishlist'] ?? '';  // Тк товары у нас хранятся в куках, мы создаем переменную $wishlist и получим нужные данные из массива $_COOKIE: если есть - заберем их, если нет запишем в $wishlist пустую строку ''
        if ($wishlist) { // Проверяем, если у нас переменная $wishlist выдает true - мы получим строку с id через запятую (1,2, итд)
            $wishlist = explode (',', $wishlist); // В этом случае нам нужно разбить эту строку на массив
        }

        if (is_array ($wishlist)) {  // Проверим, если мы получили массив $wishlist, то мы с ним будем работать
            $wishlist = array_slice ($wishlist, 0, 6);  // Ограничиваем количество добавлений товаров в избранное (у нас 6 максимум)
            $wishlist = array_map ('intval', $wishlist); // Нам нужно привести предыдущий результат к числовой форме. Используем функцию array_map, которая применит коллбэк 'intval' функцию к каждому элементу массива $wishlist - каждый элемент будет приведен к числу
            return $wishlist; // Вернем $wishlist - массив id-шников
        }
        return [];
    }

    public function get_wishlist_products ($lang): array { // Метод модели, который будет по id-шникам получать товары из списка избранного

        $wishlist = self::get_wishlist_ids ();  // Вызовем метод get_wishlist_ids - для получения либо пустого массива, либо массива id-шников
        if ($wishlist) { // Если у нас $wishlist массив не пуст
            $wishlist = implode (',', $wishlist); // Мы его разобьем по разделителю методом implode и получим строку с id-шниками
            return R::getAll ("SELECT p.*, pd.* FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND p.id IN ($wishlist) AND pd.language_id = ? LIMIT 6", [$lang['id']]); // Вернем нужные нам данные из БД по id-шникам
        }
        return []; // Если у нас $wishlist массив пуст, мы вернем пустой массив

    }

    public function delete_from_wishlist ($id): bool { // Метод для удаления товаров из избранного. Принимает аргументов $id товара
        $wishlist = self::get_wishlist_ids ();  // Вызовем метод get_wishlist_ids - для получения либо пустого массива, либо массива id-шников
        $key = array_search ($id, $wishlist); // Мы ищем и возвращаем ключ $id товара для удаления из избранного в массиве $wishlist

        if (false !== $key) { // Проверяем, если в массиве есть такой товар и мы можем его удалить
            unset($wishlist[$key]); // Удаляем товар из массива избранного
            if ($wishlist) { // Проверка, сколько элементов осталось в массиве $wishlist - если не пустой - Нужно перезаписать куку
                $wishlist = implode (',', $wishlist); // Мы его разобьем по разделителю методом implode и получим строку с id-шниками
                setcookie ('wishlist', $wishlist, time() + 3600*24*7*4, '/'); // Сохраняем строку в куки

            } else { // Иначе, если мы удалили последний элемент и массив стал пустой
                setcookie ('wishlist', '', time()-3600, '/');  // Удалим куку 'wishlist' и запишем пустую строку и от метки времени time()-3600 (принято отнимать отнимать один час) для всего домена '/'
            }
            return true;
        }
        return false;
    }

}