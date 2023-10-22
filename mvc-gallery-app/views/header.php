<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Управление галереями и изображениями</title>
  <style>
    ul {
      list-style-type: none;
      padding: 0;
      background-color: #f4f4f4;
      padding: 10px;
    }

    li {
      margin-bottom: 10px;
      background-color: #ffffff;
      padding: 10px;
      border-radius: 5px;
    }

    .category {
      color: #007bff;
      font-weight: bold;
    }

    a {
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }

    .container {

      margin-bottom: 111px;

    }
  </style>
</head>

<body>

  <ul>
    <li class="category">Галереи:</li>
    <li><a href="../views/gallery-management.php"><i class="fas fa-images"></i> Управление галереями</a></li>
    <li class="category">Изображения:</li>
    <li><a href="../views/image-management.php"><i class="fas fa-image"></i> Управление изображениями</a></li>
  </ul>




  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>