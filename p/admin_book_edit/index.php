<?php require_once("../../session.php") ?>
<?php _adminsOnly() ?>
<?php

if (!isset($_GET["u"])) {
  header("Location: ../books");
  die();
}

$book = Db::getBook($_GET["u"], false);
if (!isset($book)) {
  header("Location: ../books");
  die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduHall</title>
  <link rel="icon" type="image/x-icon" href="../../images/favicon.png">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="admin_book_edit.css">
</head>

<body>
  <?php include("../../partials/header.php"); ?>

  <main>

    <form>

      <!-- სურათი და წელი -->
      <div class="admin_book_edit-grid">
        <label class="file_image">
          <svg xmlns="http://www.w3.org/2000/svg" height="36px" viewBox="0 0 24 24" width="36px" fill="var(--clr_c2)">
            <path d="M0 0h24v24H0V0z" fill="none" />
            <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z" />
          </svg>
          <img id="imgPreview" src="../../images/<?php echo $book["image"] ?>">
          <input type="file" accept="image/png, image/jpeg, image/jpg, image/jpeg, image/webp" />
        </label>
        <label class="txt" style="align-self: start">
          <?php tr("გამოშვების წელი", "year") ?>
          <input id="txtYear" name="year" type="number" value="<?php echo $book["year"] ?>" required />
        </label>
      </div>

      <!-- დასახელება -->
      <div class="admin_book_edit-grid">

        <label class="txt">
          <div>
            <img width="18" src="../../images/united-kingdom.png" />
            <?php tr("დასახელება", "title") ?>
          </div>
          <input id="txtTitle" name="book_title" value="<?php echo $book["title"] ?>" required />
        </label>

        <label class="txt">
          <div>
            <img width="18" src="../../images/georgia.png" />
            <?php tr("დასახელება ქართულად", "title georgian") ?>
          </div>
          <input id="txtTitleGeo" name="book_title_geo" value="<?php echo $book["title_geo"] ?>" required />
        </label>

      </div>


      <!-- ავტორები -->
      <div class="admin_book_edit-grid">

        <label class="txt">
          <div>
            <img width="18" src="../../images/united-kingdom.png" />
            <?php tr("ავტორები", "authors") ?>
          </div>
          <input id="txtAuthors" name="authors" value="<?php echo $book["authors"] ?>" required />
        </label>

        <label class="txt">
          <div>
            <img width="18" src="../../images/georgia.png" />
            <?php tr("ავტორები ქართულად", "authors georgian") ?>
          </div>
          <input id="txtAuthorsGeo" name="authors_geo" value="<?php echo $book["authors_geo"] ?>" required />
        </label>

      </div>


      <!-- აღწერა -->
      <div class="admin_book_edit-grid">

        <label class="txt">
          <div>
            <img width="18" src="../../images/united-kingdom.png" />
            <?php tr("აღწერა", "description") ?>
          </div>
          <textarea id="txtDescription"><?php echo $book["description"] ?></textarea>
        </label>

        <label class="txt">
          <div>
            <img width="18" src="../../images/georgia.png" />
            <?php tr("აღწერა ქართულად", "description georgian") ?>
          </div>
          <textarea id="txtDescriptionGeo"><?php echo $book["description_geo"] ?></textarea>
        </label>
      </div>

      <button id="btnSubmit"><?php tr("შენახვა", "submit") ?></button>

      <script>
        const id = <?php echo $book["id"] ?>;
        const image = "<?php echo $book["image"] ?>";
        const ui = {
          img: document.querySelector("input[type='file']"),
          imgPreview: document.querySelector("#imgPreview"),
          txtTitle: document.querySelector("#txtTitle"),
          txtTitleGeo: document.querySelector("#txtTitleGeo"),
          txtYear: document.querySelector("#txtYear"),
          txtAuthors: document.querySelector("#txtAuthors"),
          txtAuthrosGeo: document.querySelector("#txtAuthorsGeo"),
          txtDescription: document.querySelector("#txtDescription"),
          txtDescriptionGeo: document.querySelector("#txtDescriptionGeo"),
          btnSubmit: document.querySelector("#btnSubmit"),
          form: document.querySelector("form")
        }

        ui.img.addEventListener('change', (e) => {
          const file = e.target.files.item(0)
          if (!file)
            return;

          ui.imgPreview.src = URL.createObjectURL(file)
        });


        ui.form.addEventListener("submit", async e => {
          e.preventDefault()
          if (ui.btnSubmit.classList.contains("disabled"))
            return

          ui.btnSubmit.classList.add("disabled")

          const file = ui.img.files[0];
          const formData = new FormData();
          formData.append("image", file);
          formData.append("book", JSON.stringify({
            id,
            image,
            title: ui.txtTitle.value,
            title_geo: ui.txtTitleGeo.value,
            authors: ui.txtAuthors.value,
            authors_geo: ui.txtAuthrosGeo.value,
            year: ui.txtYear.value,
            description: ui.txtDescription.value,
            description_geo: ui.txtDescriptionGeo.value
          }))

          const response = fetch("/alpharocket/api/book_edit.php", {
              method: 'POST',
              body: formData
            })
            .then(response => response.json())
            .then(res => {
              if (res.error_text) {
                alert(res.error_text)
                ui.btnSubmit.classList.remove("disabled")
                return
              }

              window.location.href = `/alpharocket/p/book?u=${id}`;
            });

        })
      </script>
    </form>
  </main>

  <?php include("../../partials/footer.php"); ?>

</body>

</html>