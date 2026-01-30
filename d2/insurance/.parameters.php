<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    'GROUPS' => [
        'USER_CARD' => [
            'NAME' => 'Параметры подключения',
        ],
    ],
    'PARAMETERS' => [
        'ADRESS' => [
            'NAME' => 'Адрес сервиса',
            'TYPE' => 'TEXT',
            'PARENT' => 'USER_CARD',
        ],
        'PRODUCT_ID' => [
            'NAME' => 'ID продукта',
            'TYPE' => 'TEXT',
            'PARENT' => 'USER_CARD',
        ],
        'LOGIN' => [
            'NAME' => 'Логин',
            'TYPE' => 'TEXT',
            'PARENT' => 'USER_CARD',
        ],
        'PASSWORD' => [
            'NAME' => 'Пароль',
            'TYPE' => 'TEXT',
            'PARENT' => 'USER_CARD',
        ],
    ],
];

?>