<?php
use wfm\View; // Импортируем(подключаем) класс вида
/** @var $this View */
?>
<?php $this->getPart ('parts/header'); ?>

<div class="container"> <!-- Отображение ошибок валидации -->
    <div class="row">
        <div class="col">
            <?php if (!empty($_SESSION['errors'])): ?> <!-- Проверка, если у нас не пуст массив с ошибками $_SESSION['errors'], тогда мы будем выводить эти ошибки перед выводом контента страницы -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['errors']; unset($_SESSION['errors']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?> <!-- Проверка, если у нас не пуст массив с успешными действиями пользователя $_SESSION['success'], тогда мы будем выводить эти сообщения об успехе  -->
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php echo $this->content ?>

<?php $this->getPart ('parts/footer'); ?>


