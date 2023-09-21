<li class="nav-item <?php if (isset($category['children'])) echo 'dropdown' ?>"> <!-- Проверяем, если мы работаем с элементом, у которого есть потомки "children" тогда мы формируем верстку одного вывода иначе другого вида -->
    <a class="nav-link <?php if (isset($category['children'])) echo 'dropdown-toggle' ?>" href="category/<?= $category['slug'] ?>" <?php if (isset($category['children'])) echo 'data-bs-toggle="dropdown"' ?>><?= $category['title'] ?></a>
    <?php if (isset($category['children'])): ?>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown"> <!-- Вкладываем тег "ul"  -->
            <?= $this->getMenuHtml($category['children']);?>
        </ul>
    <?php endif; ?>
</li>
