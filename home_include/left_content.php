<?php include("include/connect.php"); ?>


<div class="container my-4">
  <!-- Danh mục Sản phẩm -->
  <div class="mb-5">
    <h4 class="text-uppercase bg-primary text-white px-3 py-2 rounded-top">Sản phẩm</h4>
    <ul class="list-group shadow-sm rounded-bottom">
      <?php 
      $sql = "SELECT * FROM danhmuc WHERE dequi = 1";
      $result = mysqli_query($conn, $sql);
      while ($row = mysqli_fetch_assoc($result)): ?>
        <li class="list-group-item border-0 d-flex justify-content-between align-items-center">
          <a href="index.php?madm=<?= $row['madm'] ?>" class="text-decoration-none text-dark flex-grow-1">
            <?= htmlspecialchars($row['tendm']) ?>
          </a>
          <i class="bi bi-chevron-right text-secondary"></i>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>

  <!-- Danh mục Phụ kiện -->
  <div class="mb-5">
    <h4 class="text-uppercase bg-success text-white px-3 py-2 rounded-top">Phụ kiện</h4>
    <ul class="list-group shadow-sm rounded-bottom">
      <?php 
      $sql = "SELECT * FROM danhmuc WHERE dequi = 2";
      $result = mysqli_query($conn, $sql);
      while ($row = mysqli_fetch_assoc($result)): ?>
        <li class="list-group-item border-0 d-flex justify-content-between align-items-center">
          <a href="index.php?madm=<?= $row['madm'] ?>" class="text-decoration-none text-dark flex-grow-1">
            <?= htmlspecialchars($row['tendm']) ?>
          </a>
          <i class="bi bi-chevron-right text-secondary"></i>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>

  <!-- Quảng cáo -->
  <div class="mb-4 rounded overflow-hidden" style="max-width: 100%; height: 300px;">
    <a href="http://localhost:3000/index.php?content=chitietsp&idsp=108">
        <img src="img/banner.jpg" alt="Quảng cáo" class="img-fluid w-100 h-100" style="object-fit: cover;">
    </a>
</div>

<div class="mb-4 rounded overflow-hidden" style="max-width: 100%; height: 300px;">
    <a href="http://localhost:3000/index.php?content=chitietsp&idsp=111">
        <img src="img/quangcao2.png" alt="Quảng cáo 2" class="img-fluid w-100 h-100" style="object-fit: cover;">
    </a>
</div>