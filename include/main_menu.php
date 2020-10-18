<?php

//Массив разделов меню сайта
$mainMenu = [
    [
        'title' => 'Главная',
        'path' => '/',
        'sort' => 4,
        'private' => false,
    ],
    [
        'title' => 'О нас',
        'path' => '/route/about_us/',
        'sort' => 5,
        'private' => false,
    ],
    [
        'title' => 'Контакты',
        'path' => '/route/contacts/',
        'sort' => 3,
        'private' => false,
    ],
    [
        'title' => 'Новости',
        'path' => '/route/news/',
        'sort' => 1,
        'private' => false,
    ],
    [
        'title' => 'Каталог',
        'path' => '/route/catalog/',
        'sort' => 2,
        'private' => false,
    ],
    [
        'title' => 'Полевые цветы в качестве дорожного покрытия',
        'path' => '/route/wild_flowers/',
        'sort' => 6,
        'private' => false,
    ],
    [
        'title' => 'Профиль пользователя',
        'path' => '/route/user_profile/',
        'sort' => 7,
        'private' => true,
    ],
    [
        'title' => 'Сообщения',
        'path' => '/posts/',
        'sort' => 8,
        'private' => true,
    ],
    [
        'title' => 'Отправить сообщения',
        'path' => '/posts/add/',
        'sort' => 9,
        'private' => true,
    ],
    [
        'title' => 'Просмотр сообщения',
        'path' => '/posts/view_message/',
        'sort' => 10,
        'private' => true,
    ],
];
