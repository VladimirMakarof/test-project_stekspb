<?php
// Подключение к базе данных
include('../config/config.php');
include('../controllers/GalleryController.php');
include('../controllers/ImageController.php');
include('../models/GalleryModel.php');
include('../models/ImageModel.php');

// Создаем объект базы данных
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);




if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Создаем объекты моделей
$galleryModel = new GalleryModel($conn);
$imageModel = new ImageModel($conn);

// $uploadPath = '../uploads/' . $previewImage['name'];
$uploadPath = '../uploads/';

// Создаем объекты контроллеров и передаем объекты моделей и базы данных
$galleryController = new GalleryController($conn, $galleryModel, $uploadPath);
$imageController = new ImageController($conn, $uploadPath);

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'];

  switch ($action) {
    case 'create':
      $galleryName = $_POST['galleryName'];
      $previewImage = $_FILES['previewImage'];


      // Проверка, что файл успешно загружен и нет ошибок
      if (isset($previewImage['error']) && $previewImage['error'] === UPLOAD_ERR_OK) {
        // Путь для сохранения файла

        $filename = uniqid() . '_' . basename($previewImage['name']);
        $uploadPath = '../uploads/' . $filename;

        // Сохранение файла
        if (move_uploaded_file($previewImage['tmp_name'], $uploadPath)) {

          // Создаем объекты моделей
          // $galleryModel = new GalleryModel($conn);
          $imageModel = new ImageModel($conn);

          // Создание галереи и получение ее ID
          $galleryId = $galleryModel->createGallery($galleryName);

          // Проверка, что галерея успешно создана
          if ($galleryId !== false) {
            // Создание контроллера изображения
            $imageController = new ImageController($conn, $uploadPath);

            // Связывание файла с галереей
            if ($imageController->uploadImage($galleryId, $previewImage)) {
              // Файл успешно связан с галереей
            } else {
              // Обработка ошибки связывания файла с галереей
              unlink($uploadPath); // Удалить файл, так как он не был связан с галереей
              echo "Ошибка при связывании файла с галереей.";
            }
          } else {
            // Обработка ошибки создания галереи
            unlink($uploadPath); // Удалить файл, так как галерея не была создана
            echo "Ошибка при создании галереи.";
          }
        } else {
          // Обработка ошибки при сохранении файла
          echo "Ошибка при сохранении файла.";
        }
      } else {
        // Обработка ошибки загрузки файла
        echo "Ошибка при загрузке файла.";
      }
      break;
    case 'rename':
      $galleryId = $_POST['galleryId'];
      $newName = $_POST['newName'];

      // Проверка, что новое имя не пустое
      if (!empty($newName)) {
        if ($galleryController->renameGallery($galleryId, $newName)) {
          // Галерея успешно переименована
          echo "Галерея успешно переименована.";
        } else {
          // Обработка ошибки переименования галереи
          echo "Ошибка при переименовании галереи.";
        }
      } else {
        // Обработка ошибки пустого нового имени
        echo "Новое имя галереи не может быть пустым.";
      }
      break;

    case 'delete':
      $galleryId = $_POST['galleryId'];

      // Проверка, что ID галереи не пустой
      if (!empty($galleryId)) {
        if ($galleryController->deleteGallery($galleryId)) {
          // Галерея успешно удалена
          echo "Галерея успешно удалена.";
        } else {
          // Обработка ошибки удаления галереи
          echo "Ошибка при удалении галереи.";
        }
      } else {
        // Обработка ошибки пустого ID галереи
        echo "ID галереи не может быть пустым.";
      }
      break;

    default:
      // Обработка неверных значений действия
      break;
  }

  // Перенаправьте пользователя на другую страницу после выполнения операции, например, обратно на gallery-management.php
  header('Location: ../views/gallery-management.php');
  exit();
}
