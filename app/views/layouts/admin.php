<?php /** @var $this \wfm\View  */ ?> <!-- Подключаем базовый класс View фреймворка, чтобы у нас были подсказки от редактора -->

<?php $this->getPart ('admin/parts/header'); ?> <!-- Подключаем хедер админки -->

<?php $this->getPart ('admin/parts/sidebar'); ?> <!-- Подключаем сайдбар админки -->


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1><?= $title ?? '' ?></h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <?php if (!empty($_SESSION['success'])): ?> <!-- Выводим Alert об успехе -->
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['errors'])): ?> <!-- Выводим Alert об ошибке -->
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $_SESSION['errors']; unset($_SESSION['errors']); ?>
                </div>
            <?php endif; ?>

            <?php echo $this->content; ?> <!-- Подключаем контент админки сайта -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

<?php $this->getPart ('admin/parts/footer'); ?> <!-- Подключаем футер админки -->