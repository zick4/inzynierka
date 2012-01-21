<?php

return array(
    array(
        'label' => 'Logowanie',
        'description' => 'Zaloguj się by wykorzystać wszystkie możliwości serwisu',
        'route' => 'login',
        'resource' => 'user',
        'privilege' => 'login',
    ),
    array(
        'label' => 'Konto',
        'description' => '',
        'route' => 'profil',
        'resource' => 'user',
        'privilege' => 'account',
        'pages' => array(
            array(
                'label' => 'Rejestracja',
                'route' => 'registration',
                'resource' => 'user',
                'privilege' => 'registration',
            ),
            array(
                'label' => 'Wylogowanie',
                'route' => 'logout',
                'resource' => 'user',
                'privilege' => 'logout',
            ),
        )
    ),
    array(
        'label' => 'Albumy',
        'route' => 'album_list',
        'description' => 'Zarządzaj sowimi albumami i zdjęciami',
        'resource' => 'album',
        'privilege' => 'link in menu',
        'pages' => array(
            array(
                'label' => 'Dodaj album',
                'route' => 'album_add',
                'resource' => 'album',
                'privilege' => 'add',
                'reset_params' => false
            ),
        )
    )
);