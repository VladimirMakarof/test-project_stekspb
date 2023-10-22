<?php
require_once('../config/config.php');
require_once('../models/GalleryModel.php');
require_once('../views/header.php');

$galleryId = isset($_GET['galleryId']) ? $_GET['galleryId'] : null;
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// $uploadPath = '../uploads/';
$galleryModel = new GalleryModel($conn);

$images = $galleryModel->getImagesByGalleryId($galleryId);
// $previewImage = null;

// if (!empty($images)) {
//   $previewImage = $images[0]['filename'];
// }

// include('../views/gallery-view.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Мета-теги и заголовок страницы -->
</head>

<body>
  <div class="container">
    <h1>Галерея: <?php echo isset($galleryName) ? htmlspecialchars($galleryName) : ''; ?></h1>

    <div>
      <?php if (isset($images) && is_array($images)) : ?>
        <?php foreach ($images as $image) : ?>
          <a href="../views/image-view.php?imageId=<?php echo $image['id']; ?>">
            <img src="../uploads/<?php echo htmlspecialchars($image['filename']); ?>" alt="Изображение">
          </a>
        <?php endforeach; ?>
      <?php else : ?>
        <p>Нет доступных изображений в этой галерее.</p>
      <?php endif; ?>
    </div>

    <a href="gallery-list.php">Назад к списку галерей.</a>
  </div>
</body>
<?php
require_once('../views/footer.php');
?>

</html>