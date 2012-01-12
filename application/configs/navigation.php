<?php

return array(
    array(
        'label'     => 'Logowanie',
        'route'     => 'login',
        'resource'  => 'user',
        'privilege' => 'login',
    ),
    array(
        'label'     => 'Konto',
        'route'     => 'profil',
        'resource'  => 'user',
        'privilege' => 'account',
        'pages'     => array(
            array(
                'label'     => 'Rejestracja',
                'route'     => 'registration',
                'resource'  => 'user',
                'privilege' => 'registration',
            ),
            array(
                'label'     => 'Wylogowanie',
                'route'     => 'logout',
                'resource'  => 'user',
                'privilege' => 'logout',
            ),
        )
    ),
    array(
        'label' => 'Albumy',
        'route' => 'album_list',
        'resource'     => 'album',
        'privilege'    => 'list',
        'pages' => array(
            array(
                'label'        => 'Dodaj album',
                'route'        => 'album_add',
                'resource'     => 'album',
                'privilege'    => 'add',
                'reset_params' => false
            ),
        )
    )
);