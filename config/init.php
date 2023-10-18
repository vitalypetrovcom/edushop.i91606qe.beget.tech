<?php

define ("DEBUG", 1); // 1 - режим разработки (отображает все ошибки разработки), 0 - режим продакшн (ошибки скрыты, выдаем ошибку 404 или 500).
define ("ROOT", dirname (__DIR__)); // Константа ведет на корень нашего приложения (new-ishop.loc)
define ("WWW", ROOT . '/public'); // Константа указывает путь к публичной папке (public)
define ("APP", ROOT . '/app'); // Константа указывает путь к папке приложения (app)
define ("CORE", ROOT . '/vendor/wfm'); // Константа указывает путь к ядру приложения (wfm)
define ("HELPERS", ROOT . '/vendor/wfm/helpers'); // Константа указывает путь к папке помошников (helpers)
define ("CACHE", ROOT . '/tmp/cache'); // Константа указывает путь к папке кэша (cache)
define ("LOGS", ROOT . '/tmp/logs'); // Константа указывает путь к папке логов (logs)
define ("CONFIG", ROOT . '/config'); // Константа указывает путь к папке конфигов (настроек) (config)
define ("LAYOUT", 'ishop'); // Шаблон сайта по умолчанию
define ("PATH", 'http://new-ishop.loc'); // Адрес нашего сайта (путь к главной странице нашего сайта)
define ("ADMIN", 'http://new-ishop.loc/admin'); // Адрес админки нашего сайта (путь к странице админки нашего сайта)
define ("NO_IMAGE", '/public/uploads/no_image.jpg'); // Путь к странице административной панели нашего сайта

require_once ROOT . '/vendor/autoload.php';




