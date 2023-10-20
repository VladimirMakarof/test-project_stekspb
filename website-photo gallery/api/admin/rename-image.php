<?php
include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $imageId = mysqli_real_escape_string($conn, $_POST['imageId']);
  $newName = mysqli_real_escape_string($conn, $_POST['newName']);

  // Получите текущее имя файла из базы данных
  $sql = "SELECT filename FROM images WHERE id='$imageId'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $currentFilename = $row['filename'];

  // Постройте полный путь к текущему файлу
  $currentFilePath = "../uploads/" . $currentFilename;

  // Постройте полный путь к новому файлу с новым именем
  $newFilePath = "../uploads/" . $newName;

  // Обновите имя файла в базе данных
  $sql = "UPDATE images SET filename='$newName' WHERE id='$imageId'";

  if ($conn->query($sql) === TRUE) {
    // Переименуйте файл на сервере
    if (rename($currentFilePath, $newFilePath)) {
      echo "Image renamed successfully";
    } else {
      echo "Error renaming file";
    }
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
