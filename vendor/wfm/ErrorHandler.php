<?php


namespace wfm;


class ErrorHandler {

    public function __construct () {
        if (DEBUG) {
            error_reporting (-1); // Включаем показ ошибок
        } else {
            error_reporting (0); // Выключаем показ ошибок
        }
        set_exception_handler ([$this, 'exceptionHandler']); // Метод для отлова исключений.
        set_error_handler ([$this, 'errorHandler']); // Метод для отлова ошибок.
        ob_start(); // Чтобы ошибка не выводилась, мы ее буферизируем (запомнить, отправить в буфер, откуда мы ее потом заберем).
        register_shutdown_function([$this, 'fatalErrorHandler']); // Метод для фатальных ошибок
    }

    public function errorHandler ($errno, $errstr, $errfile, $errline) { // Метод для отлова ошибок.

        $this->logError($errstr, $errfile, $errline);
        $this->displayError($errno, $errstr, $errfile, $errline);

    }

    public function fatalErrorHandler () { // Метод для фатальных ошибок

        $error = error_get_last (); // Получаем последнюю ошибку и записываем ее в переменную "$error".

        if (!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) { // Проверяем, если она у нас не пуста И тип ошибки соотвествует тем, которые мы сможем обработать:
            $this->logError($error['message'], $error['file'], $error['line']); // В таком случае, мы логируем ошибку
            ob_end_clean(); // Выключаем буфер, который включили ранее "ob_start()"
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line']); // Мы пытаемся показать ошибку
        } else { // В противном случае, если что-то не выполнилось -
            ob_end_flush(); // Выключаем буфер и завершаем данный метод
        }

    }

    public function exceptionHandler (\Throwable $e) { // Метод для отлова исключений. В обьекте "е" будет хранится вся информация об ошибках (текст, файл с исключением и ошибкой, строка кода итд)

        $this->logError($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError('Исключение', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());


    }

    protected function logError ($message = '', $file = '', $line = '') { // Метод Запись в логи ошибок ($message - текст ошибки, $file - название файла с ошибкой, $line - строка ошибки)

        file_put_contents (
            LOGS . '/errors.log',
            "[" . date('Y-m-d H:i:s') . "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\n=================\n",
            FILE_APPEND); // Ошибку необходимо "залогировать" (сохранить в какой-либо файл).

    }

    protected function displayError ($errno, $errstr, $errfile, $errline, $responce = 500) { // Метод Показ ошибок на экран ($errno - номер ошибки, $errstr - строка ошибки, $errfile - файл с ошибкой, $errline - линия ошибки, $responce - код ответа)

        if ($responce == 0) {
            $responce = 404;
        }
        http_response_code($responce); // Позволяет отправить необходимый код ответа в заголовках
        if ($responce == 404 && !DEBUG) { // Определяем, какую страницу показать пользователю (если у нас 404 ошибка(страница не найдена) и включен ли у нас режим отладки (выключен режим отладки) - мы находимся в режиме "продакшн" и мы должны показать стандартную страницу 404
            require WWW . '/errors/404.php';
            die; // Завершаем выполнение скрипта
        }
        if (DEBUG) { // Если у нас не выполнилось ни одно из условий (код не 404) и "DEBUG" включен:
            require WWW . '/errors/development.php'; // Подключим файл "development.php" с подробной информацией об ошибке
        } else {
            require WWW . '/errors/production.php'; // Иначе, подключим файл "production.php" (страница - заглушка, что-то не так на сайте ...)
        }
        die; // Завершаем выполнение скрипта
    }

}