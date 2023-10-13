<?php
$parent_id = \wfm\App::$app->getProperty('parent_id'); /* $parent_id берем из контейнера App::$app->getProperty. Оно будет использовано в будущем при редактировании категории (чтобы при редактировании категории мы не могли выбрать текущую категорию родительской для самой себя) */
$get_id = get('id');
?>

<option value="<?= $id ?>" <?php if ($id == $parent_id) echo ' selected'; ?> <?php if ($get_id == $id) echo ' disabled'; ?>>
    <?= $tab . $category['title'] ?>
</option>
<?php if(isset($category['children'])): ?>
    <?= $this->getMenuHtml($category['children'], '&nbsp;' . $tab. '-') ?>
<?php endif; ?>
