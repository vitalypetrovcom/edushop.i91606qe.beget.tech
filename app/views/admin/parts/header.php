<?php
use wfm\View; // Импортируем(подключаем) класс вида
/** @var $this View */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?= ADMIN ?>"> <!-- Указываем базовый путь к админке для всего сайта используя константу ADMIN -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->getMeta () ?> <!-- Устанавливаем тайтл страницы -->

    <link rel="icon" type="image/png" size="32x32" href="<?= PATH ?>/assets/img/favicon.png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= PATH ?>/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= PATH ?>/adminlte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= PATH ?>/adminlte/main.css"> <!-- Подключаем файл main.css -->
    <link rel="stylesheet" href="<?= PATH ?>/adminlte/plugins/select2/css/select2.min.css"> <!-- Подключаем файл select2.min.css -->

    <script src="<?= PATH?>/adminlte/ckeditor/ckeditor.js"></script> <!-- Рекомендуется подключать файлы CKEditor в header админки. Подключаем файл ckeditor.js -->
    <script src="<?= PATH?>/adminlte/ckfinder/ckfinder.js"></script> <!-- Подключаем файл ckfinder.js -->


</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->