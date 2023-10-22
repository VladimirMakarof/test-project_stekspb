<?php
require_once('../config/config.php');
require_once('../controllers/GalleryController.php');
require_once('../controllers/ImageController.php');
require_once('../models/GalleryModel.php');
require_once('../models/ImageModel.php');
require_once('../views/header.php');

$imageModel = new ImageModel($conn);
// $filename = $imageModel->$uploadPath = '../uploads/' . $filename;
$image = $imageModel->getImageById($_GET['imageId']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Просмотр изображения</title>
</head>

<body>
  <div class="container">
    <h1>Просмотр изображения</h1>

    <div>

      <img src="../uploads/<?php echo htmlspecialchars($image['filename']); ?>" alt="Изображение">
    </div>

    <a href="../public/index.php">Назад к списку галерей</a>
  </div>
</body>
<?php
require_once('../views/footer.php');
?>

</html>