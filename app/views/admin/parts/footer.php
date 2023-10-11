<?php
use wfm\View; // Импортируем(подключаем) класс вида
/** @var $this View */
?>
<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<div class="logs">
    <?php $this->getDbLogs (); ?> <!-- Добавляем логи на страницу -->
</div>

<script>
    const PATH = '<?= PATH ?>'; <!-- Добавляем константы -->
    const ADMIN = '<?= ADMIN ?>';
</script>

<!-- jQuery -->
<script src="<?= PATH ?>/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= PATH ?>/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= PATH ?>/adminlte/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="<?php /*= PATH */?>/adminlte/dist/js/demo.js"></script>-->
<script src="<?= PATH ?>/adminlte/main.js"></script> <!-- Подключаем файл main.js -->

</body>
</html>
<!--<script>
    (function() {  // Скрипт создания WebSocket
        var ws = new WebSocket('ws://' + window.location.host +
            '/jb-server-page?reloadMode=RELOAD_ON_SAVE&'+
            'referrer=' + encodeURIComponent(window.location.pathname));
        ws.onmessage = function (msg) {
            if (msg.data === 'reload') {
                window.location.reload();
            }
            if (msg.data.startsWith('update-css ')) {
                var messageId = msg.data.substring(11);
                var links = document.getElementsByTagName('link');
                for (var i = 0; i < links.length; i++) {
                    var link = links[i];
                    if (link.rel !== 'stylesheet') continue;
                    var clonedLink = link.cloneNode(true);
                    var newHref = link.href.replace(/(&|\?)jbUpdateLinksId=\d+/, "$1jbUpdateLinksId=" + messageId);
                    if (newHref !== link.href) {
                        clonedLink.href = newHref;
                    }
                    else {
                        var indexOfQuest = newHref.indexOf('?');
                        if (indexOfQuest >= 0) {
                            // to support ?foo#hash
                            clonedLink.href = newHref.substring(0, indexOfQuest + 1) + 'jbUpdateLinksId=' + messageId + '&' +
                                newHref.substring(indexOfQuest + 1);
                        }
                        else {
                            clonedLink.href += '?' + 'jbUpdateLinksId=' + messageId;
                        }
                    }
                    link.replaceWith(clonedLink);
                }
            }
        };
    })();
</script>-->