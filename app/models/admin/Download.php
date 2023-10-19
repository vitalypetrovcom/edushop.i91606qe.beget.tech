<?php


namespace app\models\admin;


use app\models\AppModel;
use RedBeanPHP\R;

class Download extends AppModel { // Модель (класс) для получения данных о цифровых товарах и прикрепляемых к ним файлах из БД

    public function get_downloads($lang, $start, $perpage): array { // Метод для получения списка файлов для цифровых товаров из БД. Принимает на вход $lang, $start, $perpage
        return R::getAll("SELECT d.*, dd.* FROM download d JOIN download_description dd on d.id = dd.download_id WHERE dd.language_id = ? LIMIT $start, $perpage", [$lang['id']]);
    }

    public function download_validate (): bool { // Метод для валидации данных при загрузке файла

        $errors = ''; // Объявим переменную $errors, в которую будем записывать все ошибки при валидации данных загружаемого файла. По умолчанию - пустая строка.
        foreach ($_POST['download_description'] as $lang_id => $item) { // Пройдемся в цикле по массиву $_POST['download_description'] и получим данные ключ-значение
            $item['name'] = trim ($item['name']); // Поле 'name' (Наименование) является обязательным. Мы это поле обрабатываем с помощью функции trim (удаление пробелов в начале и в конце записи)
            if (empty($item['name'])) { // Если пустое значение переменной $item['name']
                $errors .= "Не заполнено наименование {$lang_id}<br>"; // В переменную $errors мы дописываем сообщение об ошибке
            }
        }

        // Будем валидировать сам передаваемый файл
        if (empty($_FILES) || $_FILES['file']['error']) { // Если пуст массив $_FILES (пользователь не отправил нам загружаемый через форму файл) ИЛИ массив $_FILES['error'] содержит ошибки
            $errors .= "Ошибка загрузки файла!<br>"; // В переменную $errors мы дописываем сообщение об ошибке
        } else {
            // Проверим расширение файла на соответствие нашему списку разрешенных файлов
            $extentions = ['jpg', 'jpeg', 'png', 'zip', 'pdf', 'txt']; // Объявим переменную $extentions в виде массива, и пропишем в нем все допустимые расширения файлов для загрузки
            $parts = explode ('.', $_FILES['file']['name']); // Получаем расширение загружаемого файла используя функцию explode - разбиваем полное наименование файла (приходит к нам из $_FILES['file']['name']) по точке "."
            $ext = end ($parts); // Берем в переменную $ext последнее значение полученного массива $parts
            if (!in_array ($ext, $extentions)) { // Если полученное расширение не входит в массив допустимых расширений файлов $extentions
                $errors .= "Допустимые для загрузки расширения: jpg, jpeg, png, zip, pdf, txt!<br>"; // В переменную $errors мы дописываем сообщение об ошибке
            }

        }

        if ($errors) { // Если переменная $errors не осталась пустой
            $_SESSION['errors'] = $errors; // В массив $_SESSION['errors'] запишем сообщения об ошибках
            return false;
        }

        return true; // Если мы прошли все условия валидации загружаемого файла, вернем true

    }

    public function upload_file (): array|false { // Метод для загрузки файла в БД

        $file_name = $_FILES['file']['name'] . uniqid (); // Объявим переменную $file_name и передадим в нее оригинальное имя файла. Но к этому имени, в целях безопасности, мы должны добавить рандомную строку (если даже пользователь знает папку, где лежит скачиваемый файл, он никак не смог скачать этот файл: 100.jpg - 100.jpg615487b659028). Это проще всего сделать с помощью функции uniqid. Тогда у нас будет оригинальное имя файла И новое рандомное имя файла (который нам нужно сохранить)
        $path = WWW . '/downloads/' . $file_name; // Определим путь, куда мы будем сохранять рандомный файл
        if (move_uploaded_file ($_FILES['file']['tmp_name'], $path)) { // Перемещаем загруженный файл в эту папку с помощью функции move_uploaded_file. В нее мы передаем данные об временном имени файла $_FILES['file']['tmp_name'] (загруженного на сервер во временную папку) и перемещаем его в новое место, которое указано в переменной $path
            // Если перемещение вновь загруженного рандомного файла удалось, мы должны вернуть массив, где будет оригинальное имя "original_name" и рандомное имя файла "filename"
            return [
                'original_name' => $_FILES['file']['name'],
                'filename' => $file_name,
            ];
        }

        return false; // Если не удалось загрузить файл в БД, вернем return false;

    }

    public function save_download ($data): bool { // Метод для записи (сохранения) информации о загружаемом файле в БД. Принимает на вход массив с информацией о загружаемом файле и вернет значение bool. Здесь нам нужна выгрузка в две таблицы и мы используем механизм транзакций

        R::begin (); // Используем транзакцию RedBeanPHP используя try-catch
        try {
            $download = R::dispense ('download'); // Первая ключевая таблица это $download, поэтому создаем объект bean $download. В этой таблице нам нужны filename и original_name
            $download->filename = $data['filename']; // Записываем в объект $download по соответствующим ключам filename и original_name
            $download->original_name = $data['original_name'];
            $download_id = R::store ($download); // Получаем id записи

            // Выгружаем данные в таблицу download_description
            foreach ( $_POST[ 'download_description' ] as $lang_id => $item) {
                R::exec ("INSERT INTO download_description (download_id, language_id, name) VALUES (?,?,?)", [
                    $download_id,
                    $lang_id,
                    $item['name'],
                ]);
            }


            R::commit (); // Если все прошло успешно, тогда мы делаем commit этих данных
            return true; // Вернем true

        } catch (\Exception $e) { // Если загрузка не удалась, мы откатываем операцию
            R::rollback (); // Можем откатить транзакцию если у нас что-то пошло не по плану
            return false; // Вернем false
        }



        }

}