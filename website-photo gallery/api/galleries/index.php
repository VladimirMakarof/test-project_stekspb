<?php
include('../config/config.php');

// Получение списка галерей
$sql = "SELECT * FROM galleries";
$result = $conn->query($sql);

// Преобразование результата в ассоциативный массив
$galleries = [];
while ($row = $result->fetch_assoc()) {
  $galleries[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Список галерей</title>
</head>

<body>
  <h1>Список галерей</h1>

  <!-- Отображение списка галерей -->
  <ul>
    <?php foreach ($galleries as $gallery) : ?>
      <li><a href="view.php?id=<?php echo $gallery['id']; ?>"><?php echo $gallery['name']; ?></a></li>
    <?php endforeach; ?>
  </ul>
</body>

</html>