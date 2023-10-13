<!-- Default box -->
<div class="card">

    <div class="card-body">

        <form action="" method="post">

            <div class="form-group"> <!-- Поле для выбора родительской категории если нам это нужно. Если нет - выбираем - "Самостоятельная категория". Из этого поля у нас общие данные пойдут в таблицу category БД -->
                <label class="required" for="parent_id">Родительская категория</label>
                <?php new \app\widgets\menu\Menu([ /* Используем виджет меню */
                    'cache' => 0,
                    'cacheKey' => 'admin_menu_select',
                    'class' => 'form-control',
                    'container' => 'select', /* для выпадающего списка */
                    'attrs' => [ /* массив атрибутов */ /* Используя метод output виджета Menu, проходимся по массиву атрибутов и получаем ключ-значение: получим строку name = 'значение, которое мы передали в parent_id' */
                        'name' => 'parent_id',
                        'id' => 'parent_id',
                        'required' => 'required',
                    ],
                    'prepend' => '<option value="0">Самостоятельная категория</option>', /* То, что выводится перед нашим основным html списком категорий. Добавляем дополнительный элемент, которого нет в БД "Самостоятельная категория" */
                    'tpl' => APP . '/widgets/menu/admin_select_tpl.php', /* Указываем путь к шаблону, который должен быть использован */
                ]) ?>
            </div>

            <div class="card card-info card-outline card-tabs"> <!-- Выводим табы с контентом с делением по языкам путем прохождения в цикле по массиву languages. Из этих полей у нас частные данные пойдут в таблицу category_description БД -->
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <?php foreach (\wfm\App::$app->getProperty('languages') as $k => $lang): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($lang['base']) echo 'active' ?>" data-toggle="pill" href="#<?= $k ?>">
                                    <img src="<?= PATH ?>/assets/img/lang/<?= $k ?>.png" alt="">
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content"> <!-- Выводим поля для ввода контента при добавлении категорий для каждого конкретного таба (для разных языков) путем прохождения в цикле по массиву languages -->
                        <?php foreach (\wfm\App::$app->getProperty('languages') as $k => $lang): ?>
                            <div class="tab-pane fade <?php if ($lang['base']) echo 'active show' ?>" id="<?= $k ?>">

                                <div class="form-group">
                                    <label class="required" for="title">Наименование</label>
                                    <input type="text" name="category_description[<?= $lang['id'] ?>][title]" class="form-control" id="title" placeholder="Наименование категории" value="<?= get_field_array_value('category_description', $lang['id'], 'title') ?>" required2>
                                </div>

                                <div class="form-group">
                                    <label for="description">Мета-описание</label>
                                    <input type="text" name="category_description[<?= $lang['id'] ?>][description]" class="form-control" id="description" placeholder="Мета-описание" value="<?= get_field_array_value('category_description', $lang['id'], 'description') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="keywords">Ключевые слова</label>
                                    <input type="text" name="category_description[<?= $lang['id'] ?>][keywords]" class="form-control" id="keywords" placeholder="Ключевые слова" value="<?= get_field_array_value('category_description', $lang['id'], 'keywords') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="content">Описание категории</label>
                                    <textarea name="category_description[<?= $lang['id'] ?>][content]" class="form-control editor" id="content" rows="3" placeholder="Описание категории"><?= get_field_array_value('category_description', $lang['id'], 'content') ?></textarea>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- /.card -->
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>

        </form>

        <?php
        if (isset($_SESSION['form_data'])) {
            unset($_SESSION['form_data']);
        }
        ?>

    </div>

</div>
<!-- /.card -->
