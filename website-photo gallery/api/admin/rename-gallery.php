<?php
include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $galleryId = mysqli_real_escape_string($conn, $_POST['galleryId']);
  $newName = mysqli_real_escape_string($conn, $_POST['newName']);

  $sql = "UPDATE galleries SET name='$newName' WHERE id='$galleryId'";

  if ($conn->query($sql) === TRUE) {
    echo "Gallery renamed successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
