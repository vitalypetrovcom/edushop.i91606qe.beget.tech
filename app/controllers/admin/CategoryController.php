<?php

namespace app\controllers\admin;

use app\models\admin\Category;
use RedBeanPHP\R;
use wfm\App;

/** @property Category $model  */

class CategoryController extends AppController { // Контроллер для работы с категориями товаров в админке

    public function indexAction () { // Метод для работы с категориями товаров в админке

        $title = 'Категории'; // Объявляем переменную $title
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title')); // Передаем саму переменную в вид

    }

    public function deleteAction () { // Метод для удаления категории в админ-панели

        $id = get ('id'); // Получаем id конкретной категории из массива $_GET по ключу 'id'
        $errors = ''; // Создаем переменную $errors (по умолчанию - пустая строка), которую мы будем заполнять различными ошибками
        $children = R::count ('category', 'parent_id = ?', [$id]); // Делаем запрос к БД и проверяем указанную категорию на наличие потомков (дочерних категорий). Если категория, которую мы хотим удалить содержит потомков - мы не разрешим удалять ее
        $products = R::count ('product','category_id = ?', [$id]); // Делаем запрос к БД и проверяем указанную категорию на наличие товаров. Если категория, которую мы хотим удалить содержит товары - мы не разрешим удалять ее
        if ($children) { // Если в переменной $children мы получаем значение больше нуля (те у данной категории с $id есть потомки)
            $errors .= 'Ошибка! В категории есть вложенные категории!<br>'; // В переменную $errors допишем текст - 'Ошибка! В категории есть вложенные категории!'
        }
        if ($products) { // Если в переменной $products мы получаем значение больше нуля (те у данной категории с $id есть товары)
            $errors .= 'Ошибка! В категории есть товары!<br>'; // В переменную $errors допишем текст - 'Ошибка! В категории есть товары!'
        }
        if ($errors) { // Если у нас есть ошибки ($errors содержит строки с ошибками)
            $_SESSION['errors'] = $errors; // Запишем ошибки в сессию

        } else {
            R::exec ("DELETE FROM category WHERE id = ?", [$id]);
            R::exec ("DELETE FROM category_description WHERE category_id = ?", [$id]); // Удаляем конкретную категорию $id из БД (таблицы: category и category_description)
            $_SESSION['success'] = 'Категория удалена'; // В массив сессии по ключу 'success' запишем сообщение, что категория была удалена
        }
        redirect (); // Делаем редирект на текущую страницу

    }

    public function addAction () { // Метод для добавления категорий в админ-панели сайта

        if (!empty($_POST)) { // Если у нас не пустой массив $_POST
            /*debug ($_POST); // Проверка правильности выполнения*/
            if ($this->model->category_validate ()) { // Мы пытаемся валидировать модель. Если мы прошли валидацию

                if ($this->model->save_category ()) { // Мы пытаемся ее сохранить. Мы должны проверить, сохранили ли мы данные этой модели. Если сохранили данные категории, тогда
                    $_SESSION['success'] = 'Категория сохранена'; // Записываем успешное сообщение в массив сессии $_SESSION['success']
                } else { // Иначе,
                    $_SESSION['errors'] = 'Ошибка сохранения категории!'; // Записываем сообщение об ошибке в массив сессии $_SESSION['errors']
                }

            }
            redirect (); // Делаем редирект на ту же страницу

        }
        $title = 'Добавление категории'; // Иначе, мы должны показать страницу с формой добавления новой категории. Объявляем переменную $title
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title')); // Передаем саму переменную в вид

    }

    public function editAction () { // Метод для редактирования категорий товаров в админ-панели

        $id = get ('id'); // Получаем $id категории из массива $_GET (id в url адресе )
        if (!empty($_POST)) { // Если админ заполнил форму изменения категории товара и отправил ее (массив $_POST не пустой), мы будем обрабатывать полученные данные
            if ($this->model->category_validate ()) { // Валидируем данные, полученные из массива $_POST
                if ($this->model->update_category ($id)) { // Если мы обновили данные категории с $id (получили true)
                    $_SESSION['success'] = 'Категория обновлена'; // Записываем успешное сообщение в массив сессии $_SESSION['success']
                } else {
                    $_SESSION['errors'] = 'Ошибка обновления категории!'; // Записываем сообщение об ошибке в массив сессии $_SESSION['errors']
                }
            }
            redirect (); // Редирект на ту же страницу
        }
        $category = $this->model->get_category ($id); // Объявляем переменную $category и передаем в нее данные конкретной категории из БД по ee $id
        /*debug ($category); // Проверка правильности выполнения*/
        if (!$category) { // Если переменная $category пуста,
            throw new \Exception('Not found category!', 404); // Выдаем ошибку 'Not found category!'
        }

        $lang = App::$app->getProperty ('language')['id']; // Получаем текущий активный язык из контейнера App
        App::$app->setProperty ('parent_id', $category[$lang]['parent_id']); // Устанавливаем в контейнер App по ключу 'parent_id' значение 'parent_id' из массива $category[$lang]['parent_id']
        $title = 'Редактирование категории'; // Объявляем переменную $title
        $this->setMeta ("Админка :: {$title}"); // Передаем название title в представление
        $this->set (compact ('title', 'category')); // Передаем саму переменную в вид

    }

}