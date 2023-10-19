<!-- Default box -->
<div class="card">

    <div class="card-body">

        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data"> <!-- Чтобы загружать файлы через форму, мы должны обязательно указать enctype="multipart/form-data" -->

            <?php foreach (\wfm\App::$app->getProperty('languages') as $k => $lang): ?> <!-- Выводим через цикл два поля (для РУ и для EN) для наименования файла -->

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label required">
                        <img src="<?= PATH ?>/assets/img/lang/<?= $k ?>.png" alt="">
                        Наименование
                    </label>
                    <div class="col-sm-9">
                        <input type="text" name="download_description[<?= $lang['id'] ?>][name]" class="form-control" id="name" placeholder="Наименование файла"> <!-- Убираем временно required, чтобы проверить клиентскую валидацию -->
                    </div>

                </div>

            <?php endforeach; ?>

            <hr>

            <span class="text-info">Допустимые для загрузки расширения: jpg, jpeg, png, zip, pdf, txt</span> <!-- Указываем допустимые типы файлов -->
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="exampleInputFile"> <!-- Убираем временно required, чтобы проверить клиентскую валидацию -->
                    <label class="custom-file-label required" for="exampleInputFile">Choose file</label> <!-- Выбрать файл на ПК -->
                </div>
                <div class="input-group-append">
                    <span class="input-group-text">Upload</span> <!-- Загрузить файл -->
                </div>
            </div>

            <br>
            <button type="submit" class="btn btn-primary">Сохранить</button>


        </form>

    </div>

</div>
<!-- /.card -->

