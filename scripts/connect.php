<?php
require("config.php");
$mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);

if ($mysqli->connect_errno) {
    die('Ошибка подключения к базе данных');
}
