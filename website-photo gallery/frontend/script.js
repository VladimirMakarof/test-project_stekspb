document.addEventListener("DOMContentLoaded", function () {
  // Загрузка списка галерей при загрузке страницы
  fetch("api/galleries/index.php")
    .then(response => response.json())
    .then(galleries => {
      const galleriesList = document.getElementById("galleries-list");
      galleries.forEach(gallery => {
        const galleryLink = document.createElement("a");
        galleryLink.href = `api/galleries/view.php?id=${gallery.id}`;
        galleryLink.textContent = gallery.name;
        galleriesList.appendChild(galleryLink);
      });
    })
    .catch(error => console.error(error));
});
