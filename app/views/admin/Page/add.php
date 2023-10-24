<!-- Default box -->
<div class="card">

    <div class="card-body">

        <form action="" method="post">

            <div class="card card-info card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0"> <!-- Для формирования двух вкладок с мета-описанием на разных языках, мы в цикле проходим по массиву языков \wfm\App::$app->getProperty('languages') -->
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <?php foreach (\wfm\App::$app->getProperty('languages') as $k => $lang): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($lang['base']) echo 'active' ?>" data-toggle="pill" href="#<?= $k ?>">
                                    <img src="<?= PATH ?>/assets/img/lang/<?= $k ?>.png" alt="">
                                </a> <!-- Ставим флажок вместо названия языка -->
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content"> <!-- Выводим в цикле форму для заполнения мета-данных на вкладке конкретного языка -->
                        <?php foreach (\wfm\App::$app->getProperty('languages') as $k => $lang): ?>
                            <div class="tab-pane fade <?php if ($lang['base']) echo 'active show' ?>" id="<?= $k ?>">

                                <div class="form-group">
                                    <label class="required" for="title">Наименование страницы</label>
                                    <input type="text" name="page_description[<?= $lang['id'] ?>][title]" class="form-control" id="title" placeholder="Наименование страницы" value="<?= get_field_array_value('page_description', $lang['id'], 'title') ?>"> <!-- Используем функцию get_field_array_value () чтобы в случае ошибок валидации в $_SESSION['form_data'] сохранить введенные данные -->
                                </div>

                                <div class="form-group">
                                    <label for="description">Мета-описание</label>
                                    <input type="text" name="page_description[<?= $lang['id'] ?>][description]" class="form-control" id="description" placeholder="Мета-описание" value="<?= get_field_array_value('page_description', $lang['id'], 'description') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="keywords">Ключевые слова</label>
                                    <input type="text" name="page_description[<?= $lang['id'] ?>][keywords]" class="form-control" id="keywords" placeholder="Ключевые слова" value="<?= get_field_array_value('page_description', $lang['id'], 'keywords') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="content" class="required">Контент страницы</label>
                                    <textarea name="page_description[<?= $lang['id'] ?>][content]" class="form-control editor" id="content" rows="3" placeholder="Контент страницы"><?= get_field_array_value('page_description', $lang['id'], 'content') ?></textarea>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- /.card -->
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>  <!-- Кнопка "Сохранить" -->

        </form>

        <?php
        if (isset($_SESSION['form_data'])) { // Если у нас были сохранены в сессии данные заполнения формы
            unset($_SESSION['form_data']); // Мы их удаляем
        }
        ?>

    </div>

</div>
<!-- /.card -->

<script> <!-- Скрипт подключения CKEditor на странице -->
    window.editors = {};
    document.querySelectorAll( '.editor' ).forEach( ( node, index ) => {
        ClassicEditor
            .create( node, {
                ckfinder: {
                    uploadUrl: '<?= PATH ?>/adminlte/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
                },
                toolbar: [ 'ckfinder', '|', 'heading', '|', 'bold', 'italic', '|', 'undo', 'redo', '|', 'link', 'bulletedList', 'numberedList', 'insertTable', 'blockQuote' ],
                image: {
                    toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight' ],
                    styles: [
                        'alignLeft',
                        'alignCenter',
                        'alignRight'
                    ]
                }
            } )
            .then( newEditor => {
                window.editors[ index ] = newEditor
            } )
            .catch( error => {
                console.error( error );
            } );
    });

</script>
