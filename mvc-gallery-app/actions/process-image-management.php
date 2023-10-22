<?php
include_once('../config/config.php');
include_once('../models/ImageModel.php');
include_once('../models/GalleryModel.php');



$imageModel = new ImageModel($conn);
$galleryModel = new GalleryModel($conn);
// $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Обработка загрузки изображения
if (isset($_POST['uploadImage'])) {
  $imageFile = $_FILES['imageFile'];
  $galleryId = $_POST['galleryId'];

  // Проверка, что файл успешно загружен и нет ошибок
  if ($imageFile['error'] === UPLOAD_ERR_OK) {
    // Получаем информацию о галерее, к которой прикрепим изображение (например, из сессии или другим способом)

    // Генерируем уникальное имя файла для изображения
    $filename = uniqid() . '_' . basename($imageFile['name']);

    // Путь для сохранения файла
    $uploadPath = '../uploads/' . $filename;

    // Перемещаем загруженный файл в целевую директорию
    if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
      // Получаем ID галереи (предположим, что у вас есть переменная $galleryId)
      // Ваш код для получения $galleryId из сессии, URL-параметров или других источников данных

      // Загружаем изображение в базу данных
      if ($imageModel->uploadImage($galleryId, $filename)) {
        // Изображение успешно загружено в базу данных
        // Выполните необходимые действия после успешной загрузки
      } else {
        // Обработка ошибки загрузки изображения в базу данных
        unlink($uploadPath); // Удалить файл, так как загрузка не удалась
        echo "Ошибка при загрузке изображения в базу данных.";
      }
    } else {
      // Обработка ошибки при сохранении файла
      echo "Ошибка при сохранении файла.";
    }
  } else {
    // Обработка ошибок загрузки файла
    echo "Ошибка при загрузке файла.";
  }
}

// Получаем список изображений из базы данных 
$images = $imageModel->getImagesByGalleryId($galleryId);

// Получаем список доступных галерей
$availableGalleries = $galleryModel->getAllGalleries();



// Передаем переменные в представление
$data['images'] = $images;
$data['availableGalleries'] = $availableGalleries;

// Включаем шаблон страницы
include('../views/image-management.php');
