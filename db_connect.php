<?php

// Подключение к базе данных

$db_host = 'localhost';
$db_user = 'docs';
$db_password = '1234';
$db_name = 'docs';

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$conn) {
    die('Ошибка подключения к базе данных: ' . mysqli_connect_error());
}

?>
