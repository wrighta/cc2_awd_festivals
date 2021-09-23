<?php require_once 'config.php'; ?>
<?php 
$festivals = Festival::findAll();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Music festivals</title>

    <link href="<?= APP_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= APP_URL ?>/assets/css/template.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <?php require 'include/header.php'; ?>
      <?php require 'include/navbar.php'; ?>
      <main role="main">
        <div>
          <h1>Our festivals</h1>
          <div class="row">
          <?php foreach ($festivals as $festival) { ?>
            <div class="col mb-4">
              <div class="card" style="width:15rem;">
                <img src="<?= APP_URL ?>/assets/img/default.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                  <h5 class="card-title"><?= $festival->title ?></h5>
                  <p class="card-text"><?= get_words($festival->description, 20) ?></p>
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Location: <?= $festival->location ?></li>
                  <li class="list-group-item">Start date: <?= $festival->start_date ?></li>
                  <li class="list-group-item">End date: <?= $festival->end_date ?></li>
                </ul>
              </div>
            </div>
          <?php } ?>
          </div>
        </div>
      </main>
      <?php require 'include/footer.php'; ?>
    </div>
    <script src="<?= APP_URL ?>/assets/js/jquery-3.5.1.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
