<?php
class GalleryModel
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  // Метод для создания новой галереи
  public function createGallery($galleryName)
  {
    $stmt = $this->db->prepare("INSERT INTO galleries (name) VALUES (?)");

    $stmt->bind_param("s", $galleryName);

    if ($stmt->execute()) {
      // Получаем ID последней вставленной записи
      $galleryId = $this->db->insert_id;
      return $galleryId;
    } else {
      return false;
    }
  }




  // Метод для переименования галереи
  public function renameGallery($galleryId, $newName)
  {
    $stmt = $this->db->prepare("UPDATE galleries SET name=? WHERE id=?");
    $stmt->bind_param("si", $newName, $galleryId);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Метод для удаления галереи
  public function deleteGallery($galleryId)
  {
    // Перед удалением галереи, удалите все связанные изображения (если это требуется в вашем приложении)

    $stmt = $this->db->prepare("DELETE FROM galleries WHERE id=?");
    $stmt->bind_param("i", $galleryId);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Метод для получения всех галерей
  public function getAllGalleries()
  {
    $sql = "SELECT id, name, preview_image_id FROM galleries";

    $result = $this->db->query($sql);

    $galleries = [];
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $galleries[] = $row;
      }
    }

    return $galleries;
  }


  // Метод для получения изображений в определенной галерее
  public function getImagesInGallery($galleryId)
  {
    $sql = "SELECT id, filename FROM images WHERE gallery_id=$galleryId";
    $result = $this->db->query($sql);

    $images = [];
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $images[] = $row;
      }
    }
    return $images;
  }

  // Метод для получения превью-изображения галереи (первое изображение в галерее)
  public function getGalleryPreviewImage($galleryId)
  {
    $sql = "SELECT filename FROM images WHERE gallery_id=$galleryId LIMIT 1";
    $result = $this->db->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return '../uploads/' . $row['filename'];
    } else {
      return '../uploads/default.webp';
    }
  }

  // Метод для удаления изображения из галереи
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
}
