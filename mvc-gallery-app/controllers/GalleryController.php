<?php
class GalleryController
{
  private $db;
  private $galleryModel;
  private $uploadPath;

  public function __construct($db, $galleryModel, $uploadPath)
  {
    $this->db = $db;
    $this->galleryModel = $galleryModel;
    $this->uploadPath = $uploadPath;
  }

  // Метод для создания новой галереи
  public function createGallery($galleryName, $galleryId, $imageFile)
  {
    // Создаем объект ImageController
    $imageController = new ImageController($this->db, $this->uploadPath);

    // Загрузите изображение и получите его ID
    $imageId = $imageController->uploadImage($galleryId, $imageFile);

    // Если загрузка изображения успешна и получен его ID, создайте галерею
    if ($imageId !== false) {
      // Ваш SQL-запрос для добавления галереи в базу данных
      $stmt = $this->db->prepare("INSERT INTO galleries (name, preview_image_id) VALUES (?, ?)");
      $stmt->bind_param("si", $galleryName, $imageId);

      if ($stmt->execute()) {
        return true;
      } else {
        // Обработка ошибки при выполнении SQL-запроса
        return false;
      }
    } else {
      // Обработка ошибки загрузки изображения
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
    // Удаление связанных изображений перед удалением галереи
    $this->deleteImagesByGallery($galleryId);

    $stmt = $this->db->prepare("DELETE FROM galleries WHERE id=?");
    $stmt->bind_param("i", $galleryId);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Метод для удаления изображений по ID галереи
  private function deleteImagesByGallery($galleryId)
  {

    $stmt = $this->db->prepare("DELETE FROM images WHERE gallery_id=?");
    $stmt->bind_param("i", $galleryId);
    $stmt->execute();
  }

  public function viewGallery($galleryId)
  {
    // Получаем информацию о галерее из базы данных, включая preview_image_id
    $gallery = $this->galleryModel->getGalleryById($galleryId);

    // Получаем изображения для галереи
    $images = $this->galleryModel->getImagesByGalleryId($galleryId);



    // Передаем данные в представление
    include('../views/gallery-view.php');
  }




  public function listGalleries()
  {
    // Запрос к базе данных для получения данных о галереях
    $query = "SELECT * FROM galleries";
    $result = $this->db->query($query);

    $galleriesData = [];



    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {

        $galleriesData[] = $row;
      }
    }

    return $galleriesData;
  }
}
