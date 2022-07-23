<?php require_once("../../session.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>EduHall - <?php tr("წიგნები", "Books") ?></title>
  <meta name="description" content="წიგნები" />
  <meta property="og:title" content="EduHall - Books" />
  <meta property="og:description" content="EduHall is one of Georgia’s leading centers for cultural relations and educational opportunities." />
  <meta property="og:url" content="https://www.eduhall.ge/" />
  <meta property="og:site_name" content="EduHall" />
  <meta property="og:image" content="/images/eduhall.png" />
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="books.css">
  <link rel="icon" type="image/x-icon" href="../../images/favicon.png">
  <script src="../../js/pdf.js"></script>
</head>

<body>

  <?php include("../../partials/header.php"); ?>
  <main>
    <?php if (_isAdmin()) : ?>
      <button class="flat" onClick="window.location.href = `/eduhall.git/p/admin_book_add`" style="margin-bottom: 2em"><?php tr("წიგნის დამატება", "add new book") ?></button>

    <?php endif; ?>

    <div class="main_grid">
      <?php $listBooks = DB::getListBooks() ?>
      <?php foreach ($listBooks as $book) : ?>
        <a href="/eduhall.git/p/book?u=<?php echo $book["id"] ?>" class="img_container">
          <img width="100%" height="336" src="../../images/<?php echo $book["image"] ?>" alt="book cover" />
        </a>
      <?php endforeach ?>

      <?php
      if (count($listBooks) < 3)
        echo "<div></div>";
      if (count($listBooks) < 2)
        echo "<div></div>";
      ?>

    </div>
  </main>

  <?php include("../../partials/footer.php"); ?>

</body>

</html>