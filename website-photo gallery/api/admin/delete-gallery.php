<?php
session_start();
include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['galleryId'])) {
  $galleryId = mysqli_real_escape_string($conn, $_POST['galleryId']);
  $sql = "DELETE FROM galleries WHERE id = '$galleryId'";
  if ($conn->query($sql) === TRUE) {
    echo "success";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
$conn->close();
