<div class="modal-body"> <!-- Модальное окно с добавленными в корзину товарами -->
    <?php if (!empty($_SESSION['cart'])): ?>  <!-- Условие выполнения - Если у нас есть что-то в корзине, мы будем выводить $_SESSION['cart'] -->
    <div class="table-responsive cart-table"> <!-- Оборачиваем наше модальное окно в адаптивную обертку Bootstrap table-responsive и добавляем класс cart-table (возможно пригодиться позднее) -->
        <table class="table text-start">
            <thead>
            <tr>
                <th scope="col">Фото</th>
                <th scope="col">Товар</th>
                <th scope="col">Кол-во</th>
                <th scope="col">Цена</th>
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
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else:  ?>    <!-- Иначе, выведем "Empty cart!" -->
    <h4 class="text-start">Empty cart!</h4>
    <?php endif; ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-success ripple" data-bs-dismiss="modal">Продолжить покупки</button> <!-- Кнопка выводится всегда -->

    <?php if (!empty($_SESSION['cart'])): ?>  <!-- Условие выполнения - Если у нас есть что-то в корзине, мы будем выводить $_SESSION['cart'] -->
    <button type="button" class="btn btn-primary">Оформить заказ</button> <!-- Кнопка выводится когда в корзине есть товары (не пуста) -->
        <button type="button" class="btn btn-danger">Очистить корзину</button> <!-- Кнопка выводится когда в корзине есть товары (не пуста) -->
    <?php endif; ?>
</div>



