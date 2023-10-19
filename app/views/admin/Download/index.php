<!-- Default box -->
<div class="card">

    <div class="card-header">
        <a href="<?= ADMIN ?>/download/add" class="btn btn-default btn-flat"><i class="fas fa-plus"></i> Загрузить файл</a>
    </div>

    <div class="card-body">

        <?php if (!empty($downloads)): ?> <!-- Если массив $downloads не пустой -->
            <table class="table table-bordered"> <!-- Выводим в табличном виде информация о файлах цифровых товаров -->
                <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Оригинальное имя</th>
                    <td width="50"><i class="far fa-trash-alt"></i></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($downloads as $download): ?> <!-- Проходим в цикле по $downloads для заполнения данными о файлах в таблице -->
                    <tr>
                        <td>
                            <?= $download['name'] ?>
                        </td>
                        <td>
                            <?= $download['original_name'] ?>
                        </td>
                        <td width="50">
                            <a class="btn btn-danger btn-sm delete" href="<?= ADMIN ?>/download/delete?id=<?= $download['id'] ?>">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-12"> <!-- Используем пагинацию на странице файлов -->
                    <p><?= count($downloads) ?> файл(ов) из: <?= $total; ?></p>
                    <?php if ($pagination->countPages > 1): ?>
                        <?= $pagination; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <p>Файлов для загрузки не найдено...</p> <!-- Если массив $downloads пустой, выводим сообщение, что файлы не найдены -->
        <?php endif; ?>

    </div>
</div>
<!-- /.card -->



