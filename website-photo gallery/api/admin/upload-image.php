<?php
include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $galleryId = mysqli_real_escape_string($conn, $_POST['galleryId']);
  $imageName = $_FILES['image']['name'];
  $imageTmpName = $_FILES['image']['tmp_name'];

  // Генерируем уникальное имя файла
  $uniqueId = uniqid();
  $fileExtension = pathinfo($imageName, PATHINFO_EXTENSION);
  $newFileName = $uniqueId . '.' . $fileExtension;

  $uploadPath = "../uploads/" . $newFileName;

  if (move_uploaded_file($imageTmpName, $uploadPath)) {
    $sql = "INSERT INTO images (gallery_id, filename) VALUES ('$galleryId', '$newFileName')";

    if ($conn->query($sql) === TRUE) {
      echo "Image uploaded successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  } else {
    echo "Error uploading image";
  }
}

$conn->close();
