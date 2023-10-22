<?php
require_once('../config/config.php');
require_once('../controllers/GalleryController.php');
require_once('../controllers/ImageController.php');
require_once('../models/GalleryModel.php');
require_once('../models/ImageModel.php');
require_once('../views/header.php');

// Подключение к базе данных с использованием переменных из конфигурационного файла
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$uploadPath = '../uploads/';
$galleryModel = new GalleryModel($conn, $uploadPath);
$imageModel = new ImageModel($conn, $uploadPath);
$galleryController = new GalleryController($conn, $galleryModel, $uploadPath);
$imageController = new ImageController($conn, $imageModel);
$galleriesData = []; // Определите переменную заранее как пустой массив

// Получаем запрошенный маршрут (например, из URL-параметра или из PATH_INFO)
$route = isset($_GET['route']) ? $_GET['route'] : '';

// Обработка маршрутов
switch ($route) {
  case 'gallery/create':
    // Обработка запроса на создание новой галереи
    if (isset($_POST['galleryName'])) {
      $galleryName = $_POST['galleryName'];
      $uploadPath = '../uploads/';
      $galleryId = 1;
      $galleryController->createGallery($galleryName, $galleryId, $uploadPath);
    }
    break;
  case 'gallery/rename':
    // Обработка запроса на переименование галереи
    if (isset($_POST['galleryId']) && isset($_POST['newName'])) {
      $galleryId = $_POST['galleryId'];
      $newName = $_POST['newName'];
      $galleryController->renameGallery($galleryId, $newName);
    }
    break;
  case 'gallery/delete':
    // Обработка запроса на удаление галереи
    if (isset($_POST['galleryId'])) {
      $galleryId = $_POST['galleryId'];
      $galleryController->deleteGallery($galleryId);
    }
    break;
  case 'image/upload':
    // Обработка запроса на загрузку изображения
    if (isset($_POST['galleryId']) && isset($_FILES['image'])) {
      $galleryId = $_POST['galleryId'];
      $imageController->uploadImage($galleryId, $_FILES['image']);
    }
    break;
  case 'image/delete':
    // Обработка запроса на удаление изображения
    if (isset($_POST['imageId']) && isset($_POST['galleryId'])) {
      $imageId = $_POST['imageId'];
      $galleryId = $_POST['galleryId'];
      $imageController->deleteImage($imageId, $galleryId);
    }
    break;
  case 'gallery/view':
    // Обработка запроса на отображение содержания галереи
    if (isset($_GET['galleryId'])) {
      $galleryId = $_GET['galleryId'];
      $galleryController->viewGallery($galleryId);
    }
    break;
  default:
    // Обработка других маршрутов, например, главной страницы
    $galleriesData = $galleryController->listGalleries();
    include('../views/gallery-list.php');
    break;
}

// Закройте соединение с базой данных после того, как все запросы выполнены и данные отображены
$conn->close();

require_once('../views/footer.php');
