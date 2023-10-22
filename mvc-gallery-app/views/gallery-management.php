<?php
// В вашем контроллере или другом файле, где вы получаете данные для представления
include('../controllers/GalleryController.php'); // Путь к вашему контроллеру
include('../models/GalleryModel.php'); // Путь к вашей модели
include('../config/config.php'); // Путь к вашему конфигурационному файлу


// Создайте объекты базы данных и модели
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$galleryModel = new GalleryModel($conn);
$uploadPath = '../uploads/';

// Создайте объект GalleryController и передайте необходимые зависимости
$galleryController = new GalleryController($conn, $galleryModel, $uploadPath);

// Получите список всех галерей
$galleries = $galleryController->listGalleries();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Управление галереями</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      padding: 20px;
    }

    a {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 20px;
      display: block;
      text-align: center;
      transition: color 0.3s;
    }

    a:hover {
      color: #0056b3;
    }

    h1 {
      font-size: 36px;
      font-weight: bold;
      color: #007bff;
      margin-bottom: 20px;
      text-align: center;
      text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.3);
    }

    form {
      max-width: 400px;
      margin: 0 auto;
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
    }

    label {
      margin-bottom: 10px;
      display: block;
      font-weight: bold;
    }

    input[type="text"],
    select,
    button,
    input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      background-color: #007bff;
      color: #ffffff;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Управление галереями</h1>
    <a href="../public/index.php">Главная страница</a>

    <form action="../actions/process-gallery-management.php" method="post" enctype="multipart/form-data">

      <label for="action">Выберите действие:</label>
      <select id="action" name="action" required>
        <option value="create">Создать галерею</option>
        <option value="rename">Переименовать галерею</option>
        <option value="delete">Удалить галерею</option>
      </select>
      <br>

      <div id="galleryNameField" style="display:none;">
        <label for="galleryName">Название галереи:</label>
        <input type="text" id="galleryName" name="galleryName">
      </div>
      <div id="galleryIdField" style="display:none;">
        <label for="galleryId">Выберите галерею:</label>

        <select id="galleryId" name="galleryId">

          <?php

          foreach ($galleries as $gallery) : ?>
            <option value="<?php echo $gallery['id']; ?>"><?php echo htmlspecialchars($gallery['name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>


      <div id="newNameField" style="display:none;">
        <label for="newName">Новое название галереи:</label>
        <input type="text" id="newName" name="newName">
      </div>
      <div id="previewImageField" style="display:none;">
        <label for="previewImage">Выберите изображение для превью:</label>
        <input type="file" id="previewImage" name="previewImage" accept="image/*">

      </div>


      <button type="submit">Submit</button>

    </form>
  </div>
  <script>
    // JavaScript для отображения соответствующих полей в зависимости от выбранного действия
    document.getElementById('action').addEventListener('change', function() {
      let action = this.value;
      document.getElementById('galleryNameField').style.display = action === 'create' ? 'block' : 'none';
      document.getElementById('galleryIdField').style.display = action === 'rename' || action === 'delete' ? 'block' : 'none';
      document.getElementById('newNameField').style.display = action === 'rename' ? 'block' : 'none';
      document.getElementById('previewImageField').style.display = action === 'create' ? 'block' : 'none';
    });
  </script>
</body>
<?php
require_once('../views/footer.php');
?>

</html>