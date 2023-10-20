<?php
include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $imageId = mysqli_real_escape_string($conn, $_POST['imageId']);
  $filename = mysqli_real_escape_string($conn, $_POST['filename']);

  // Удаляем изображение из базы данных
  $sql = "DELETE FROM images WHERE id='$imageId'";
  if ($conn->query($sql) === TRUE) {
    // Удаляем файл изображения из папки
    $imagePath = '../uploads/' . $filename;
    if (file_exists($imagePath)) {
      unlink($imagePath); // Удаляем файл
    }
    echo "success";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
