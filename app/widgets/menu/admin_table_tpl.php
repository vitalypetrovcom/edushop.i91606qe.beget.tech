<?php
$parent = isset($category['children']); /* Получаем переменную $parent и проверяем существуют ли у родительских категорий потомки */
if (!$parent) { // Если переменная $parent записана не родительская категория
    $delete = '<a class="btn btn-danger btn-sm delete" href="' . ADMIN . '/category/delete?id=' . $id . '"><i class="far fa-trash-alt"></i></a>'; // Добавляем в таблицу кнопку "корзина" удаления категории
} else { // Если родительская категория
    $delete = ''; // Вместо значения - пустая строка (кнопки "корзина" не будет)
}

$edit = '<a class="btn btn-info btn-sm" href="' . ADMIN . '/category/edit?id=' . $id . '"><i class="fas fa-pencil-alt"></i></a>'; // Добавление кнопки для редактирования для каждой категории
?>
<tr>
    <td> <!-- Меню категорий выводим в виде таблицы - строка для каждой категории и 3 колонки: название категории, кнопка "edit" и кнопка "delete" -->
        <a href="<?= ADMIN ?>/category/edit/?id=<?= $id ?>" style="padding-left: <?= strlen($tab)*3 ?>px"><?= $tab . $category['title'] ?></a> <!-- Название категории. Здесь мы организовали визуальные отступы слева для выделения вложенных (наследники) категорий со знаком "-" -->
    </td>
    <td width="50"><?= $edit ?></td> <!-- Кнопка "edit" -->
    <td width="50"><?= $delete ?></td> <!-- Кнопка "delete" -->
</tr>
<?php if (isset($category['children'])): ?> <!-- Если у нас существуют потомки -->
    <?= $this->getMenuHtml($category['children'], $tab . '&#8211;');?> <!-- Тогда мы рекурсивно вызываем метод getMenuHtml -->
<?php endif; ?>
