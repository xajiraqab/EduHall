<?php
require_once("../../session.php");
_usersOnly();
?>

<!DOCTYPE html>

<html lang="en">

<head>
  <title><?php tr("პროფილი", "Profile") ?></title>
  <meta name="description" content="Edu Hall is one of Georgia’s leading centers for cultural relations and educational opportunities." />
  <meta property="og:title" content="Edu Hall - Profile" />
  <meta property="og:description" content="Edu Hall is one of Georgia’s leading centers for cultural relations and educational opportunities." />
  <meta property="og:url" content="https://www.eduhall.ge/" />
  <meta property="og:site_name" content="one of Georgia’s leading centers for cultural relations and educational opportunities." />
  <meta property="og:image" content="/images/eduhall.png" />
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="profile.css">
  <link rel="icon" type="image/x-icon" href="../../images/favicon.png">
</head>

<body>
  <?php include("../../partials/header.php") ?>



  <dialog <?php echo isset($_SESSION["book_code_to_activate"]) ? 'open' : '' ?>>
    <div class="dialog_content" <?php echo isset($_SESSION["book_code_to_activate"]) ? 'style="animation: none"' : '' ?>>
      <form id="form_code">
        <h2><?php tr("შეიყვანეთ წიგნის კოდი", "enter book code") ?></h2>

        <!-- წიგნის კოდი -->
        <div class="flex">
          <label class="txt">
            <?php tr("კოდი", "code") ?>
            <input id="txtCode" type="text" required value="<?php echo isset($_SESSION["book_code_to_activate"]) ? $_SESSION["book_code_to_activate"] : "" ?>" />
          </label>
        </div>

        <!-- გააქტიურება -->
        <button id="btnSubmitCode">
          <div id="loadingIndicator"></div><?php tr("გააქტიურება", "activate book") ?>
        </button>

        <!-- წარმატების მაჩვენებელი -->
        <div id="lblSuccess">
          <svg xmlns="http://www.w3.org/2000/svg" height="124px" viewBox="0 0 24 24" width="124px" fill="#000000">
            <path d="M0 0h24v24H0V0z" fill="none" />
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z" />
          </svg>
          <span id="lblSuccessText"></span>
        </div>

        <!-- შეცდომის მაჩვენებელი -->
        <div id="lblError">
          <svg xmlns="http://www.w3.org/2000/svg" height="124px" viewBox="0 0 24 24" width="124px" fill="#000000">
            <path d="M11 15h2v2h-2v-2zm0-8h2v6h-2V7zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
          </svg>
          <span id="lblErrorText"></span>
        </div>

        <!-- წიგნის პირდაპირ გააქტიურებისას ფორმის საფარი :3 -->
        <div id="form_cover_for_activating_book_code" style="display: <?php echo isset($_SESSION["book_code_to_activate"]) ? "block" : "none" ?>"></div>
      </form>

      <script>
        const ui = {
          dialog: document.querySelector("dialog"),
          txtCode: document.querySelector("#txtCode"),
          formCode: document.querySelector("#form_code"),
          btnSubmitCode: document.querySelector("#btnSubmitCode"),
          lblSuccess: document.querySelector("#lblSuccess"),
          lblSuccessText: document.querySelector("#lblSuccessText"),
          lblError: document.querySelector("#lblError"),
          lblErrorText: document.querySelector("#lblErrorText"),
          formCover: document.querySelector("#form_cover_for_activating_book_code"),
          dlgContent: document.querySelector(".dialog_content"),
        }

        // დიალოგის დახურვა
        const closeDialog = () => {
          ui.btnSubmitCode.classList.remove("disabled")
          ui.dialog.open = false
          ui.lblSuccess.style.display = "none"
          ui.lblError.style.display = "none"
          ui.formCover.style.display = "none"
          ui.dlgContent.removeAttribute("style")
        }

        // დიალოგის დახურვა
        ui.dialog.addEventListener("click", e => {
          if (e.target !== e.currentTarget || ui.btnSubmitCode.classList.contains("disabled"))
            return
          closeDialog()
        })

        // ატვირთვა
        ui.formCode.addEventListener("submit", e => {
          e.preventDefault()

          if (ui.btnSubmitCode.classList.contains("disabled") || ui.lblSuccess.style.display === "grid" || ui.lblError.style.display === "grid")
            return;

          const code = ui.txtCode.value

          ui.btnSubmitCode.classList.add("disabled")

          JN.post("book_code_activate", {
              code
            },
            res => {
              ui.lblSuccessText.innerText = `„${res.book_name}“ - ${isGeorgian ? "წიგნი წარმატებით გააქტიურდა" : "book activated"}`
              ui.lblSuccess.style.display = "grid"
              ui.formCover.style.display = "none"
              setTimeout(() => window.location.reload(), 3000);
            },
            error => {

              ui.lblErrorText.innerText = error
              ui.lblError.style.display = "grid"
              ui.formCover.style.display = "none"

              setTimeout(() => {
                ui.lblError.style.display = "none"
                ui.btnSubmitCode.classList.remove("disabled")
                ui.formCode.style.pointerEvents = "all"
              }, 3000)

            })



        })

        <?php if (isset($_SESSION["book_code_to_activate"])) : ?>
          ui.btnSubmitCode.click()
        <?php endif ?>

        <?php unset($_SESSION["book_code_to_activate"]) ?>
      </script>
  </dialog>

  <?php include_once("dlgPasswordChange.php") ?>

  <main class="profile">

    <!-- პროფილი -->
    <h2 style="display: flex; justify-content: space-between">
      <?php tr("პროფილი", "profile") ?>

      <!-- გასვლა -->
      <button id="btnLogout" class="flat red with_icon">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
          <path d="M0 0h24v24H0z" fill="none" />
          <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
        </svg>
        <?php tr("გასვლა", "logout") ?>
      </button>

    </h2>

    <!-- ფოსტა -->
    <p>
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="var(--clr_primary)">
        <path d="M0 0h24v24H0V0z" fill="none" />
        <path d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z" />
      </svg>
      <?php echo $_user["email"] ?>
      <button style="visibility: hidden; margin-left: auto" class="action orange">
        <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px" fill="white">
          <path d="M0 0h24v24H0z" fill="none"></path>
          <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"></path>
        </svg>
      </button>
    </p>

    <!-- ტელეფონი -->
    <p>
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="var(--clr_primary)">
        <path d="M0 0h24v24H0z" fill="none" />
        <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56-.35-.12-.74-.03-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z" />
      </svg>
      <?php echo $_user["phone"] ?>
      <button style="visibility: hidden; margin-left: auto" class="action orange">
        <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px" fill="white">
          <path d="M0 0h24v24H0z" fill="none"></path>
          <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"></path>
        </svg>
      </button>
    </p>


    <!-- პაროლის შეცვლა -->
    <div>
      <button id="btnChangePassword" class="flat with_icon" style="font-style: italic; background: none; padding-left: 0; padding-top: .5em">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="var(--clr_primary)">
          <g fill="none">
            <path d="M0 0h24v24H0V0z" />
            <path d="M0 0h24v24H0V0z" opacity=".87" />
          </g>
          <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" />
        </svg>
        <?php tr("პაროლის შეცვლა", "change password") ?>
      </button>
    </div>

    <!-- აქტივაციის მაჩვენებელი -->
    <?php if (!$_user["is_active"]) : ?>
      <p style="color: var(--clr_red); font-weight: 500">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="var(--clr_red)">
          <path d="M0 0h24v24H0z" fill="none" />
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
        </svg>
        <?php tr("დაელოდეთ აქტივაციას", "Wait for activation") ?>
      </p>
    <?php endif ?>


    <!-- ჩემი წიგნები -->
    <div class="books">
      <h2>
        <?php $listMyBooks = Db::getListMyBooks($_user["id"]) ?>
        <?php
        tr("ჩემი წიგნები", "My books");
        echo count($listMyBooks) ? " (" . count($listMyBooks) . ")" : "";
        ?>

        <?php if ($_user && $_user["is_active"]) : ?>

          <!-- წიგნის დამატება -->
          <button style="margin-left: auto" class="with_icon flat" onclick="ui.dialog.open = true; ui.txtCode.focus()">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="var(--clr_primary)">
              <path d="M0 0h24v24H0V0z" fill="none" />
              <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
            </svg>
            <?php echo tr("წიგნის დამატება", "enter book code") ?>
          </button>
        <?php endif ?>
      </h2>

      <?php foreach ($listMyBooks as $book) : ?>
        <a href="../book/?u=<?php echo $book["id"] ?>"><?php echo $book["title" . ($_isGeorgian ? "_geo" : "")] ?></a>
      <?php endforeach ?>

    </div>


  </main>

  <script>
    document.querySelector("#btnLogout").addEventListener("click", () => JN.post("logout", {}, data => window.location.reload(), error => alert(error)))
    document.querySelector("#btnChangePassword").addEventListener("click", () => document.querySelector("#dlgPasswordChange").open = true)
  </script>

  <?php include("../../partials/footer.php"); ?>

</body>

</html>