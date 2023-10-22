<?php
class ImageModel
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  // Метод для загрузки изображения в базу данных
  public function uploadImage($galleryId, $filename)
  {
    $stmt = $this->db->prepare("INSERT INTO images (gallery_id, filename) VALUES (?, ?)");
    $stmt->bind_param("is", $galleryId, $filename);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Метод для переименования изображения
  public function renameImage($imageId, $newName)
  {
    $stmt = $this->db->prepare("UPDATE images SET filename=? WHERE id=?");
    $stmt->bind_param("si", $newName, $imageId);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Метод для удаления изображения
  public function deleteImage($imageId)
  {
    $stmt = $this->db->prepare("DELETE FROM images WHERE id=?");
    $stmt->bind_param("i", $imageId);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function getImagesByGalleryId($galleryId)
  {
    $stmt = $this->db->prepare("SELECT * FROM images WHERE gallery_id = ?");
    $stmt->bind_param("i", $galleryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $images = [];

    while ($row = $result->fetch_assoc()) {
      $images[] = $row;
    }

    return $images;
  }

  public function getImageById($imageId)
  {
    $stmt = $this->db->prepare("SELECT * FROM images WHERE id = ?");
    $stmt->bind_param("i", $imageId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $image = $result->fetch_assoc();
      return $image;
    } else {
      return null; // Изображение не найдено
    }
  }
}
