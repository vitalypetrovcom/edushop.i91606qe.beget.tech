<?php

use wfm\View; // Импортируем(подключаем) класс вида

/** @var $this View */

?>
<?php $this->getPart ('parts/header');?>


<?= $this->content; ?>


<?php $this->getPart ('parts/footer');?>
