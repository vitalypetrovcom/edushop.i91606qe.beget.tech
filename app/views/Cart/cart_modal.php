<div class="modal-body"> <!-- Модальное окно с добавленными в корзину товарами -->
    <?php if (!empty($_SESSION['cart'])): ?>  <!-- Условие выполнения - Если у нас есть что-то в корзине, мы будем выводить $_SESSION['cart'] -->
    <div class="table-responsive cart-table"> <!-- Оборачиваем наше модальное окно в адаптивную обертку Bootstrap table-responsive и добавляем класс cart-table (возможно пригодиться позднее) -->
        <table class="table text-start">
            <thead>
            <tr>
                <th scope="col"><?php __ ('tpl_cart_photo'); ?></th>
                <th scope="col"><?php __ ('tpl_cart_product'); ?></th>
                <th scope="col"><?php __ ('tpl_cart_qty'); ?></th>
                <th scope="col"><?php __ ('tpl_cart_price'); ?></th>
                <th scope="col"><i class="far fa-trash-alt"></i></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                <tr>
                <td>
                    <a href="product/<?= $item['slug'] ?>"><img src="<?= PATH . $item['img']?>" alt=""></a>
                </td>
                <td><a href="product/<?= $item['slug'] ?>"><?= $item['title'] ?></a></td>
                <td><?= $item['qty'] ?></td>
                <td>$<?= $item['price'] ?></td>
                <td><a href="cart/delete?id=<?= $id ?>" data-id="<?= $id ?>" class="del-item"><i class="far fa-trash-alt"></i></a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" class="text-end"><?php __ ('tpl_cart_total_qty') ?></td> <!-- Устанавливаем переводную фразу для "ИТОГО количество" -->
                <td class="cart-qty"><?= $_SESSION['cart.qty'] ?></td> <!-- Устанавливаем значение для "ИТОГО количество" -->
            </tr>
            <tr>
                <td colspan="4" class="text-end"><?php __ ('tpl_cart_sum') ?></td> <!-- Устанавливаем переводную фразу для "Итоговая СУММА" -->
                <td class="cart-sum">$<?= $_SESSION['cart.sum'] ?></td> <!-- Устанавливаем значение для "Итоговая СУММА" -->
            </tr>
            </tbody>
        </table>
    </div>
    <?php else:  ?>    <!-- Иначе, выведем "Empty cart!" -->
    <h4 class="text-start"><?php __ ('tpl_cart_empty') ?></h4>
    <?php endif; ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-success ripple" data-bs-dismiss="modal"><?php __ ('tpl_cart_btn_continue'); ?></button> <!-- Кнопка выводится всегда -->

    <?php if (!empty($_SESSION['cart'])): ?>  <!-- Условие выполнения - Если у нас есть что-то в корзине, мы будем выводить $_SESSION['cart'] -->
    <button type="button" class="btn btn-primary"><?php __ ('tpl_cart_btn_order'); ?></button> <!-- Кнопка выводится когда в корзине есть товары (не пуста) -->
        <button type="button" id="clear-cart" class="btn btn-danger"><?php __ ('tpl_cart_btn_clear'); ?></button> <!-- Кнопка выводится когда в корзине есть товары (не пуста) -->
    <?php endif; ?>
</div>



