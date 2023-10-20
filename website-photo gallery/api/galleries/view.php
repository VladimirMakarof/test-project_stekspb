<?php
include('../config/config.php');

if (isset($_GET['id'])) {
  $galleryId = mysqli_real_escape_string($conn, $_GET['id']);

  // Подготовленный запрос для выборки названия галереи
  $galleryNameSql = "SELECT name FROM galleries WHERE id = ?";
  $stmt = $conn->prepare($galleryNameSql);
  $stmt->bind_param("i", $galleryId);
  $stmt->execute();
  $stmt->bind_result($galleryName);
  $stmt->fetch();
  $stmt->close();

  // Подготовленный запрос для выборки изображений
  $imagesSql = "SELECT * FROM images WHERE gallery_id = ?";
  $stmt = $conn->prepare($imagesSql);
  $stmt->bind_param("i", $galleryId);
  $stmt->execute();
  $result = $stmt->get_result();

  // Выводим HTML заголовок и начало тела страницы с названием галереи
  echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
          <meta charset='UTF-8'>
          <meta name='viewport' content='width=device-width, initial-scale=1.0'>
          <title>Галерея - $galleryName</title>
          <style>
            .gallery-image {
              max-width: 300px;
              margin: 10px;
            }
          </style>
        </head>
        <body>
          <h1>Изображения в галерее '$galleryName'</h1>";

  // Проверяем, есть ли изображения в галерее
  if ($result->num_rows > 0) {
    // Выводим изображения
    while ($row = $result->fetch_assoc()) {
      $imagePath = '../uploads/' . $row['filename'];
      // Проверяем, существует ли файл изображения
      if (file_exists($imagePath)) {
        echo "<img src='$imagePath' alt='Изображение' class='gallery-image'>";
      } else {
        echo "Файл изображения не найден.";
      }
    }
  } else {
    echo "Нет изображений в этой галерее.";
  }

  // Закрываем соединение с базой данных
  $stmt->close();
  $conn->close();

  // Заканчиваем HTML-разметку
  echo "</body></html>";
}
