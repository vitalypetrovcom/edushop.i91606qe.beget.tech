<?php

namespace app\models;

use RedBeanPHP\R;

class Cart extends AppModel { // Модель для работы с корзиной товаров

    public function get_product ($id, $lang): array { // Метод получения информации о продукте по $id, $lang

        return R::getRow ("SELECT p.*, pd.* FROM product p JOIN product_description pd on p.id = pd.product_id WHERE p.status = 1 AND p.id = ? AND pd.language_id = ?", [$id, $lang['id']]); // Метод вернет одну строку с информацией о продукте

    }

    public function add_to_cart ($product, $qty = 1) { // Метод будет добавлять товары в корзину. Будет принимать значения: $product, который нужно добавить и $qty, которое нужно добавить (по умолчанию = 1)

        $qty = abs ($qty); // Функция abs вернет число без знака минус

        if ($product['is_download'] && isset($_SESSION['cart'][$product['id']])) { // Проверка товара является ли он цифровым(скачиваемым). В таком случае, пользователь должен добавить в корзину цифровой товар только один раз. Если товар цифровой И уже лежит в нашей корзине, тогда мы ничего не делаем
            return false;
        }

        if (isset($_SESSION['cart'][$product['id']])) { // Проверка, если у нас существует в корзине переданный продукт (не цифровой товар), тогда мы должны увеличить количество этого продукта
            $_SESSION['cart'][$product['id']]['qty'] += $qty;

        } else { // Иначе товар не существует в нашей корзине, тогда мы должны положить данный товар в корзину
            if ($product['is_download']) { // Если это продукт цифровой и пользователь в карточке товара указал количество более 1 штуки, тогда мы должны это количество записать как 1 штука.
                $qty = 1;
            }

            $_SESSION['cart'][$product['id']] =  [ // Наполняем массив корзины данными по $product['id'] о приобретаемом товаре
                'title' => $product['title'],
                'slug' => $product['slug'],
                'price' => $product['price'],
                'qty' => $qty,
                'img' => $product['img'],
                'is_download' => $product['is_download'],
                ];
        }

        $_SESSION['cart.qty'] = !empty($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;  // Заполнить количество товара в корзине $_SESSION['cart.qty']: если кладем товар повторно (тогда нам нужно этот элемент увеличить на нужное количество товара), если класть товар в корзину в первый раз (элемента нет в корзине, тогда добавляем нужное количество товара)
        $_SESSION['cart.sum'] = !empty($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * $product['price'] : $qty * $product['price'];
        return true;

    }

    public function delete_item ($id) { // Метод для удаления товара из корзины
        $qty_minus = $_SESSION['cart'][$id]['qty']; // Переменная, что мы будем отнимать в качестве количества товара
        $sum_minus = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price']; // Переменная, что мы будем отнимать в качестве суммы товара
        $_SESSION['cart.qty'] -= $qty_minus; // Обращаемся к "$_SESSION['cart.qty']" и отнимаем от него "$qty_minus"
        $_SESSION['cart.sum'] -= $sum_minus; // Обращаемся к "$_SESSION['cart.sum']" и отнимаем от него "$sum_minus"
        unset($_SESSION['cart'][$id]); // Удаляем значение "$_SESSION['cart'][$id]"
    }

}




/** Пример, как выглядит массив корзины */
/*Array
(
    [product_id] => Array
        (
            [qty] => QTY
            [title] => TITLE
            [price] => PRICE
            [img] => IMG
        )
    [product_id] => Array
        (
            [qty] => QTY
            [title] => TITLE
            [price] => PRICE
            [img] => IMG
        )
    )
    [cart.qty] => QTY, // Итоговое количество всех товаров в корзине
    [cart.sum] => SUM // Итоговая сумма всех товаров в корзине
*/
