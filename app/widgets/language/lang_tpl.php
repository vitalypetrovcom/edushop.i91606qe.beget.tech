<!--Дефолтный шаблон языкового виджета-->
<div class="dropdown d-inline-block">
    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
        <img src="<?= PATH ?>/assets/img/lang/<?= \wfm\App::$app->getProperty ('language')['code'] ?>.png" alt="">
    </a>
    <ul class="dropdown-menu" id="languages">
        <?php foreach ($this->languages as $k => $v): ?>  <!--В ключе "k" - содержится код языка, в значении "v" - вся остальная информация об языке-->
            <?php if (\wfm\App::$app->getProperty ('language')['code'] == $k) continue; ?> <!-- Если это условие сработает, значит этот язык уже является активным текущим языком и мы его пропускаем -->
        <?php endforeach; ?>
        <li>
            <button class="dropdown-item" data-langcode="<?= $k ?>">
                <img src="<?= PATH ?>/assets/img/lang/<?= $k ?>.png" alt="">
                <?= $v['title'] ?></button>
        </li>
    </ul>
</div>