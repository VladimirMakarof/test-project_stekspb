<?php
include('../config/config.php');

// Обработка создания миниатюры (просто пример, вы можете реализовать свою логику)

function createThumbnail($sourcePath, $thumbnailPath, $width, $height)
{
  list($sourceWidth, $sourceHeight) = getimagesize($sourcePath);
  $sourceImage = imagecreatefromjpeg($sourcePath);
  $thumbnailImage = imagecreatetruecolor($width, $height);
  imagecopyresampled($thumbnailImage, $sourceImage, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);
  imagejpeg($thumbnailImage, $thumbnailPath);
}

// Получение списка миниатюр для определенной галереи (просто пример, вы можете реализовать свою логику)

if (isset($_GET['galleryId'])) {
  $galleryId = mysqli_real_escape_string($conn, $_GET['galleryId']);
  $sql = "SELECT * FROM images WHERE gallery_id = '$galleryId'";
  $result = $conn->query($sql);

  // Создание миниатюр для изображений в галерее (просто пример, вы можете реализовать свою логику)
  while ($row = $result->fetch_assoc()) {
    $imageFilename = $row['filename'];
    $sourcePath = '../uploads/' . $imageFilename;
    $thumbnailPath = '../thumbnails/' . $imageFilename;
    createThumbnail($sourcePath, $thumbnailPath, 150, 150);
  }

  // Отправить сообщение об успешном создании миниатюр
  echo json_encode(["message" => "Thumbnails created successfully."]);
}
