<?php

function debug ($data, $die = false) { // Функция для наглядной распечатки массивов или объектов

    echo '<pre>' . print_r ($data, 1) .  '</pre>';
    if ($die) {
        die;
    }

}

function h ($str) { // Функция для обработки данных, которые могут прийти динамически: там где не нужно выполнение html кода, данные от пользователя или неизвестного источника (могут содержать вредоносный код - XSS уязвимость) - их обрабатывают с помощью htmlspecialchars или htmlentities

    return htmlspecialchars ($str);

}














