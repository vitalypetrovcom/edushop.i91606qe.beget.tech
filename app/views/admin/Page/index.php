<!-- Default box -->
<div class="card">

    <div class="card-header">
        <a href="<?= ADMIN ?>/page/add" class="btn btn-default btn-flat"><i class="fas fa-plus"></i> Добавить страницу</a>  <!-- Кнопка "Добавить страницу", которая ведет на контроллер /page и метод /add -->
    </div>

    <div class="card-body">

        <?php if (!empty($pages)): ?> <!-- Если массив $pages не пустой, мы будем выводить список страниц в виде таблицы -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th> <!-- Заголовки таблицы -->
                        <th>Наименование</th>
                        <td width="50"><i class="fas fa-pencil-alt"></i></td>
                        <td width="50"><i class="far fa-trash-alt"></i></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pages as $page): ?> <!-- Заполняем таблицу данными проходя в цикле по массиву $pages -->
                        <tr>
                            <td><?= $page['id'] ?></td>
                            <td>
                                <?= $page['title'] ?>
                            </td>
                            <td width="50">
                                <a class="btn btn-info btn-sm"
                                   href="<?= ADMIN ?>/page/edit?id=<?= $page['id'] ?>"><i
                                        class="fas fa-pencil-alt"></i></a>  <!-- Кнопка редактирования страницы -->
                            </td>
                            <td width="50">
                                <a class="btn btn-danger btn-sm delete"
                                   href="<?= ADMIN ?>/page/delete?id=<?= $page['id'] ?>">
                                    <i class="far fa-trash-alt"></i> <!-- Кнопка удаления страницы -->
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-12"> <!-- Используем пагинацию для страницы -->
                    <p><?= count($pages) ?> страниц(а) из: <?= $total; ?></p>
                    <?php if ($pagination->countPages > 1): ?>
                        <?= $pagination; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?> <!-- Если страниц не существует (массив $pages пустой) -->
            <p>Страниц не найдено...</p>
        <?php endif; ?>

    </div>
</div>
<!-- /.card -->
