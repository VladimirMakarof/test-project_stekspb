<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Управление изображениями</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      padding: 20px;
    }

    h1,
    h2 {
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
      margin-bottom: 20px;
    }

    label {
      margin-bottom: 10px;
      display: block;
      font-weight: bold;
    }

    input[type="file"],
    select,
    button {
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

    ul {
      list-style: none;
      padding: 0;
    }

    ul li {
      font-size: 18px;
      margin-bottom: 10px;
    }

    /* Стили для ссылки на главную страницу */
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
  </style>
</head>

<body>
  <div class="container">
    <h1>Управление изображениями</h1>

    <a href="../public/index.php">Главная страница</a>

    <!-- Форма для загрузки изображения -->
    <form action="../actions/process-image-management.php" method="post" enctype="multipart/form-data">
      <label for="imageFile">Выберите изображение:</label>
      <input type="file" name="imageFile" id="imageFile" required>

      <!-- Выпадающий список с доступными галереями -->
      <label for="galleryId">Выберите галерею:</label>
      <select name="galleryId" id="galleryId" required>
        <?php foreach ($galleries as $gallery) : ?>
          <option value="<?php echo $gallery['id']; ?>"><?php echo htmlspecialchars($gallery['name']); ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit" name="uploadImage">Загрузить изображение</button>
    </form>

    <h2>Список изображений</h2>
    <ul>
      <!-- Проверка, является ли $images массивом или объектом -->
      <?php if (is_array($images) || is_object($images)) : ?>
        <!-- Вывод списка изображений из базы данных -->
        <?php foreach ($images as $image) : ?>
          <li><?= $image['filename'] ?></li>
        <?php endforeach; ?>
      <?php else : ?>
        <li>Изображения не были найдены.</li>
      <?php endif; ?>
    </ul>
  </div>
</body>
<?php
require_once('../views/footer.php');
?>

</html>