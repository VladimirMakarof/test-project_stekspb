<?php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '1234');
define('DB_DATABASE', 'gallery_app');

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);


// Проверяем соединение
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// } else {
//   echo "Connected successfully";
// }


// Закрываем соединение
// $conn->close();
