<?php
session_start();
include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createGallery'])) {
  // Проверка наличия данных из формы
  if (isset($_POST['galleryName'])) {
    $galleryName = mysqli_real_escape_string($conn, $_POST['galleryName']);

    // Проверка, существует ли previewImage в $_POST
    $previewImageId = isset($_POST['previewImage']) ? mysqli_real_escape_string($conn, $_POST['previewImage']) : null;

    // Используйте подготовленный запрос для вставки данных
    $stmt = $conn->prepare("INSERT INTO galleries (name, preview_image_id) VALUES (?, ?)");
    $stmt->bind_param("si", $galleryName, $previewImageId);

    if ($stmt->execute()) {
      // После успешного создания галереи перенаправляем пользователя на index.php
      header("Location: ../index.php?success=1");
      exit(); // Важно завершить выполнение скрипта после перенаправления
    } else {
      echo "Ошибка при выполнении запроса: " . $stmt->error;
    }

    // Закрываем подготовленный запрос
    $stmt->close();
  } else {
    echo "Ошибка: Некорректные данные из формы.";
  }
}

$conn->close();
