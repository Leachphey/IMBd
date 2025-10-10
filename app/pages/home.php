  <?php include 'includes/header.php'; ?>



  <?php
  $query = "SELECT * FROM movies ORDER BY release_year DESC LIMIT 4";
  $rows = query($query);
  ?>

  <div class="container py-2 px-3 px-lg-10">

    <div class="row gx-4 ">

      <?php include 'includes/home-card-top.php'; ?>

      <?php if (!empty($rows)): ?>
        <div class="col-lg-4">

          <p class="fs-3 fw-bold ">Çıkış tarihine göre</p>
          <?php foreach ($rows as $row): ?>
            <?php include 'includes/home-card-top-card.php'; ?>
          <?php endforeach; ?>
        </div>

      <?php else: ?>
        <div class="col-12">Bilgi yok</div>
      <?php endif; ?>
    </div>
  </div>



  <!-- dizi filim liste widget 1 -->

  <div class="container my-3">
    <p class="fs-3 fw-bold ">En son eklenenler</p>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-3 justify-content-center">



      <?php
      $query = "select * from movies order by id desc limit 6";
      $rows = query($query);
      if ($rows) {
        foreach ($rows as $row) {
          include 'includes/home-card.php';
        }
      } else
        echo "Bilgi yok";

      ?>

    </div>
  </div>


  <!-- dizi filim liste widget 2 -->


  <div class="container my-3">
    <p class="fs-3 fw-bold ">İzleme listesi</p>

    <?php if (!logged_in()): ?>

      <div class="text-center my-4">
        <p class="text-muted">İzleme listenize erişmek için giriş yapmalısınız.</p>
        <a href="<?= ROOT ?>/signin" class="btn btn-primary">Giriş Yap</a>
      </div>


    <?php else: ?>

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-3 justify-content-center">
        <?php
        $user_id = user('id');
        $query = "SELECT m.* FROM movies m 
                JOIN watchlist w ON w.movie_id = m.id 
                WHERE w.user_id = :uid 
                ORDER BY m.rating DESC LIMIT 6";
        $rows = query($query, ['uid' => $user_id]);

        if ($rows) {
          foreach ($rows as $row) {
            include 'includes/home-card.php';
          }
        } else {
          echo "<div class='text-center '>İzleme listeniz boş.</div>";
          echo "<br>";
          echo "<br>";
          echo "<br>";
          echo "<br>";
          echo "<br>";
        }
        ?>
      </div>

    <?php endif; ?>
  </div>




  <div class="container my-3">
    <p class="fs-3 fw-bold">En çok beğenilen diziler</p>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-3 justify-content-center">

      <?php
      $query = "SELECT * FROM movies WHERE type = 'series' ORDER BY rating DESC LIMIT 6";
      $rows = query($query);
      if ($rows) {
        foreach ($rows as $row) {
          include 'includes/home-card.php';
        }
      } else {
        echo "<div class='text-center text-muted'>Bilgi yok</div>";
      }
      ?>

    </div>
  </div>




  <div class="container my-3">
    <p class="fs-3 fw-bold">En çok beğenilen filimler</p>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-3 justify-content-center">

      <?php
      $query = "SELECT * FROM movies WHERE type = 'movie' ORDER BY rating DESC LIMIT 6";
      $rows = query($query);
      if ($rows) {
        foreach ($rows as $row) {
          include 'includes/home-card.php';
        }
      } else {
        echo "<div class='text-center text-muted'>Bilgi yok</div>";
      }
      ?>

    </div>
  </div>



  <?php include 'includes/footer.php'; ?>

  </body>

  </html>