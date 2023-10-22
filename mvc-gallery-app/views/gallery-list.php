<?php
require_once '../controllers/GalleryController.php';
require_once '../models/GalleryModel.php';
require_once '../config/config.php';
require_once '../views/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Список галерей</title>
  <style>
    .page-title {
      font-size: 36px;
      font-weight: bold;
      color: #007bff;
      margin-bottom: 20px;
      text-align: center;
      text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.3);
    }

    .gallery-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .gallery-item {
      width: 200px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1 class="page-title">Список галерей</h1>


    <div class="gallery-container">
      <?php
      $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
      $galleryModel = new GalleryModel($conn); // Инициализируем объект GalleryModel

      $uploadPath = '../uploads/';
      $galleryController = new GalleryController($conn, $galleryModel, $uploadPath);

      if (isset($galleryController)) {
        $galleriesData = $galleryController->listGalleries();
        foreach ($galleriesData as $gallery) {
          $previewImage = $galleryModel->getGalleryPreviewImage($gallery['id']);
          echo '<div class="gallery-item">';
          echo '<a href="../views/gallery-view.php?galleryId=' . $gallery['id'] . '"><strong>' . htmlspecialchars($gallery['name']) . '</strong></a>';
          if (!empty($previewImage)) {
            echo '<br>';
            echo '<img src="' . htmlspecialchars($previewImage) . '" alt="Превью" width="150">';
          }
          echo '</div>';
        }
      } else {
        echo "Ошибка: Объект контроллера галереи не инициализирован.";
      }
      ?>
    </div>
  </div>
</body>
<?php
require_once('../views/footer.php');
?>

</html>