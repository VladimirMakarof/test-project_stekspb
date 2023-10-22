<style>
  footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 20px;
    position: fixed;
    bottom: 0;
    width: 100%;
    height: 60px;
  }

  .footer-content {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .footer-content p {
    margin: 5px 0;
  }

  .footer-content a {
    color: #007bff;
    text-decoration: none;
  }

  .footer-content a:hover {
    text-decoration: underline;
  }

  .fab.fa-github {
    font-size: 36px;
    margin-right: 10px;
    transition: transform 0.3s ease-in-out;
  }

  .fab.fa-github:hover {
    transform: scale(1.2);
  }
</style>
<footer>
  <div class="footer-content">
    <p>Сайт создан с помощью PHP и MySQL.</p>
    <p>Посетите мой <a href="https://github.com/VladimirMakarof"><i class="fab fa-github"></i> GitHub</a> </p>
  </div>
</footer>