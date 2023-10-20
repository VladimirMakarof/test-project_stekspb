<?php
include('../config/config.php');

// Обработка загрузки изображения
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['uploadImage'])) {
  $galleryId = mysqli_real_escape_string($conn, $_POST['galleryId']);
  $imageName = $_FILES['image']['name'];
  $imageTmpName = $_FILES['image']['tmp_name'];

  // Путь к папке, в которой сохраняются изображения
  $uploadPath = "../uploads/" . basename($imageName);

  // Переместить изображение в папку uploads
  if (move_uploaded_file($imageTmpName, $uploadPath)) {
    // Сохранить информацию о изображении в базе данных
    $sql = "INSERT INTO images (gallery_id, filename) VALUES ('$galleryId', '$imageName')";
    $conn->query($sql);
  }
}

// Получение списка изображений для определенной галереи
if (isset($_GET['galleryId'])) {
  $galleryId = mysqli_real_escape_string($conn, $_GET['galleryId']);
  $sql = "SELECT * FROM images WHERE gallery_id = '$galleryId'";
  $result = $conn->query($sql);

  // Преобразование результата в ассоциативный массив
  $images = [];
  while ($row = $result->fetch_assoc()) {
    $images[] = $row;
  }

  // Отправить список изображений в формате JSON
  header('Content-Type: application/json');
  echo json_encode($images);
}
