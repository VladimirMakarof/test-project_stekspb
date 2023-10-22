<?php
class ImageController
{
  private $db;
  private $uploadPath;

  public function __construct($db, $uploadPath)
  {
    $this->db = $db;
    $this->uploadPath = $uploadPath;
  }

  // Метод для загрузки изображения

  public function uploadImage($galleryId, $imageFile)
  {
    // Генерируем уникальное имя файла для изображения
    $filename = uniqid() . '_' . basename($imageFile['name']);
    $targetFilePath = '../uploads/' . $filename;


    // Перемещаем загруженный файл в целевую директорию
    if (move_uploaded_file($imageFile['tmp_name'], $targetFilePath)) {
      // echo 'сюда';
      // exit();
      // Сохраняем информацию об изображении в базе данных
      $stmt = $this->db->prepare("INSERT INTO images (gallery_id, filename) VALUES (?, ?)");
      $stmt->bind_param("is", $galleryId, $filename);

      if ($stmt->execute()) {
        return true;
      } else {
        // Если произошла ошибка при сохранении информации об изображении, удаляем файл
        unlink($targetFilePath);
        return false;
      }
    } else {
      return false;
    }
  }

  // Метод для удаления изображения
  public function deleteImage($imageId, $filename)
  {
    // Удаляем изображение из базы данных
    $stmt = $this->db->prepare("DELETE FROM images WHERE id=?");
    $stmt->bind_param("i", $imageId);
    if ($stmt->execute()) {
      // Удаляем файл изображения из сервера
      $filePath = $this->uploadPath . $filename;
      if (file_exists($filePath)) {
        unlink($filePath);
      }
      return true;
    } else {
      return false;
    }
  }
}
