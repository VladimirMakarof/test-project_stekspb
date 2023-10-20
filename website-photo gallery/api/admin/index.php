<?php
session_start();
include('../config/config.php');

try {
  // Проверяем соединение с базой данных
  if ($conn->connect_error) {
    die("Ошибка соединения с базой данных: " . $conn->connect_error);
  }

  // Обработка формы для создания новой галереи
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createGallery'])) {
    $galleryName = mysqli_real_escape_string($conn, $_POST['galleryName']);
    $sql = "INSERT INTO galleries (name) VALUES ('$galleryName')";

    // Выполняем запрос к базе данных
    $conn->query($sql);

    // Проверяем успешность выполнения запроса
    if ($conn->error) {
      echo "Ошибка при выполнении запроса: " . $conn->error;
    }
  }

  // Получаем список всех галерей
  $sql = "SELECT * FROM galleries";
  $result = $conn->query($sql);

  // Создаем массив для хранения галерей
  $galleries = [];
  while ($row = $result->fetch_assoc()) {
    $galleries[] = $row;
  }

  // Получаем список всех изображений
  $sql = "SELECT images.id, images.filename, galleries.name AS galleryName FROM images 
            INNER JOIN galleries ON images.gallery_id = galleries.id";
  $result = $conn->query($sql);

  // Создаем массив для хранения изображений
  $images = [];
  while ($row = $result->fetch_assoc()) {
    $images[] = $row;
  }
} catch (Exception $e) {
  echo "Ошибка: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <title>Админ-панель</title>
</head>

<body>
  <h1>Галереи</h1>
  <h2>Создание новой галереи</h2>
  <!-- Форма для создания новой галереи -->
  <form method="post">
    <label for="galleryName">Новая галерея:</label>
    <label for="previewImage">Выберите изображение для превью:</label>
    <select name="previewImage" id="previewImage">
      <option value="">-- Не выбрано --</option>
      <?php
      // Получите список всех изображений из базы данных
      $sql = "SELECT * FROM images";
      $result = $conn->query($sql);
      while ($row = $result->fetch_assoc()) {
        $imageId = $row['id'];
        $imageName = $row['filename'];
        echo "<option value='$imageId'>$imageName</option>";
      }
      ?>
    </select>

    <input type="text" id="galleryName" name="galleryName">
    <button type="submit" name="createGallery">Создать</button>
  </form>
  <h2>Загрузка изображения</h2>
  <!-- Форма для загрузки изображения -->
  <form id="uploadForm" enctype="multipart/form-data">
    <label for="galleryId">Выберите галерею:</label>
    <select name="galleryId" id="galleryId">
      <?php
      // Генерация опций списка из массива галерей
      foreach ($galleries as $gallery) {
        $galleryId = $gallery['id'];
        $galleryName = $gallery['name'];
        echo "<option value='$galleryId'>$galleryName</option>";
      }
      ?>
    </select>

    <br>

    <label for="image">Выберите изображение:</label>
    <input type="file" name="image" id="image">

    <br>

    <button type="submit" name="submit">Загрузить</button>
  </form>

  <h2>Список всех галерей</h2>
  <!-- Список существующих галерей -->
  <ul>
    <?php
    // Генерация списка галерей из массива галерей
    foreach ($galleries as $gallery) {
      $galleryId = $gallery['id'];
      $galleryName = $gallery['name'];
      echo "<li><a href='../galleries/view.php?id=$galleryId'>$galleryName</a> 
                  <button onclick='renameGallery($galleryId)'>Переименовать</button> 
                  <button onclick='deleteGallery($galleryId)'>Удалить</button></li>";
    }
    ?>
  </ul>

  <h2>Список всех изображений</h2>
  <!-- Таблица с изображениями и кнопкой удаления -->
  <table border="1">
    <tr>
      <th>ID</th>
      <th>Имя файла</th>
      <th>Галерея</th>
      <th>Переименовать</th>
      <th>Удалить</th>
    </tr>
    <?php
    // Генерация таблицы с изображениями и кнопкой удаления
    foreach ($images as $image) {
      $imageId = $image['id'];
      $filename = $image['filename'];
      $galleryName = $image['galleryName'];
    ?>
      <tr>
        <td><?php echo $imageId; ?></td>
        <td><?php echo $filename; ?></td>
        <td><?php echo $galleryName; ?></td>
        <td>
          <button onclick="renameImage(<?php echo $imageId; ?>, '<?php echo $filename; ?>')">Переименовать</button>
        </td>
        <td>
          <button onclick='deleteImage(<?php echo $imageId; ?>, "<?php echo $filename; ?>")'>Удалить</button>
        </td>
      </tr>
    <?php } ?>
  </table>


  <script>
    function deleteGallery(galleryId) {
      if (confirm("Вы уверены, что хотите удалить эту галерею?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete-gallery.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            // Обновить список галерей после успешного удаления
            var response = xhr.responseText;
            if (response === "success") {
              // Галерея успешно удалена, обновляем список
              var galleryElement = document.getElementById("gallery-" + galleryId);
              if (galleryElement) {
                galleryElement.remove(); // Удаляем элемент галереи из списка
              }
            } else {
              console.error("Ошибка при удалении галереи: " + response);
            }
          }
        };
        xhr.send("galleryId=" + galleryId);
      }
    }

    function renameGallery(galleryId) {
      var newName = prompt("Введите новое название галереи:");
      if (newName !== null) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "rename-gallery.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            // Обновить страницу после успешного переименования
            window.location.reload();
          } else {
            console.error("Ошибка запроса: " + xhr.status);
          }
        };
        xhr.send("galleryId=" + galleryId + "&newName=" + newName);
      }
    }

    function deleteImage(imageId, filename) {
      if (confirm("Вы уверены, что хотите удалить это изображение?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete-image.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            // Обновить страницу после успешного удаления
            var response = xhr.responseText;
            if (response === "success") {
              // Изображение успешно удалено, обновляем таблицу
              var imageRow = document.getElementById("image-" + imageId);
              if (imageRow) {
                imageRow.remove(); // Удаляем строку с изображением из таблицы
              }
            } else {
              console.error("Ошибка при удалении изображения: " + response);
            }
          }
        };
        xhr.send("imageId=" + imageId + "&filename=" + filename);
      }
    }

    // Обработчик события отправки формы
    document.getElementById('uploadForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Предотвращаем стандартное поведение формы (перезагрузку страницы)

      var formData = new FormData(this); // Получаем данные формы

      // Создаем объект XMLHttpRequest для отправки данных на сервер
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload-image.php', true);
      xhr.onload = function() {
        // Обрабатываем ответ от сервера
        if (xhr.status === 200) {
          // Действия при успешной загрузке (можете обновить таблицу изображений, если нужно)
          console.log('Изображение успешно загружено.');
        } else {
          // Действия при ошибке загрузки
          console.error('Ошибка при загрузке изображения.');
        }
      };

      // Отправляем данные на сервер
      xhr.send(formData);
    });

    function renameImage(imageId, currentName) {
      var newName = prompt("Введите новое имя изображения:", currentName);
      if (newName !== null) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "rename-image.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            // Обновить строку в таблице после успешного переименования
            var response = xhr.responseText;
            if (response === "Image renamed successfully") {
              // Изображение успешно переименовано, обновляем текст в ячейке таблицы
              var cell = document.getElementById("filename-" + imageId);
              if (cell) {
                cell.textContent = newName; // Обновляем текст ячейки с именем файла
              }
            } else {
              console.error("Ошибка при переименовании изображения: " + response);
            }
          }
        };
        xhr.send("imageId=" + imageId + "&newName=" + newName);
      }
    }
  </script>

</body>

</html>

<?php
$conn->close();
?>