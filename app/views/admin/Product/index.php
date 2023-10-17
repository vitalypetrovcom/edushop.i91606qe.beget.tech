<!-- Default box -->
<div class="card">

    <div class="card-header">
        <a href="<?= ADMIN ?>/product/add" class="btn btn-default btn-flat"><i class="fas fa-plus"></i> Добавить товар</a>
    </div>

    <div class="card-body">

        <?php if (!empty($products)): ?> <!-- Если массив $products не пустой -->
            <div class="table-responsive"> <!-- Вывод таблицы информации о товарах -->
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Фото</th>
                        <th>Наименование</th>
                        <th>Цена</th>
                        <th>Статус</th>
                        <th>Цифровой товар</th>
                        <td width="50"><i class="fas fa-pencil-alt"></i></td>
                        <td width="50"><i class="far fa-trash-alt"></i></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $product): ?> <!-- Вывод информации о товаре (заполнение таблицы товаров) в цикле-->
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td>
                                <img src="<?= PATH ?>/<?= $product['img'] ?>" alt="" height="40">
                            </td>
                            <td>
                                <?= $product['title'] ?>
                            </td>
                            <td>
                                $<?= $product['price'] ?>
                            </td>
                            <td>
                                <?= $product['status'] ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>' ?>
                            </td>
                            <td> <!-- Проверка на цифровой или обычный товар -->
                                <?= $product['is_download'] ? 'Цифровой товар' : 'Обычный товар'; ?>
                            </td>
                            <td width="50">
                                <a class="btn btn-info btn-sm"
                                   href="<?= ADMIN ?>/product/edit?id=<?= $product['id'] ?>"><i
                                        class="fas fa-pencil-alt"></i></a> <!-- Ссылка на редактирование товара -->
                            </td>
                            <td width="50">
                                <a class="btn btn-danger btn-sm delete"
                                   href="<?= ADMIN ?>/product/delete?id=<?= $product['id'] ?>">
                                    <i class="far fa-trash-alt"></i>
                                </a> <!-- Ссылка на удаление товара -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-12"> <!-- Пагинация страницы с товарами -->
                    <p><?= count($products) ?> товар(ов) из: <?= $total; ?></p>
                    <?php if ($pagination->countPages > 1): ?>
                        <?= $pagination; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <p>Товаров не найдено...</p>
        <?php endif; ?>

    </div>
</div>
<!-- /.card -->
