<?php
require_once("../../session.php");

if ($_user) {
  header("Location: ../profile");
  die();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
  <title><?php tr("შესვლა", "Login") ?></title>
  <meta name="description" content="შესვლა" />
  <meta property="og:title" content="Edu Hall - Login" />
  <meta property="og:description" content="Edu Hall is one of Georgia’s leading centers for cultural relations and educational opportunities." />
  <meta property="og:url" content="https://www.eduhall.ge/" />
  <meta property="og:site_name" content="one of Georgia’s leading centers for cultural relations and educational opportunities." />
  <meta property="og:image" content="/images/eduhall.png" />
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="login.css">
  <link rel="icon" type="image/x-icon" href="../../images/favicon.png">
</head>

<body>
  <?php include("../../partials/header.php") ?>
  <form class="grid">

    <h2><?php tr("შესვლა", "login") ?></h2>

    <!-- ფოსტა -->
    <div class="txtContainer">
      <label class="txt">
        <?php tr("ფოსტა / ტელეფონი", "email / phone") ?>
        <input id="txtEmailPhone" name="email" type="text" autofocus required />
      </label>
      <span id="lblErrorEmail">*<?php tr("ფოსტა არასოწრია", "wrong email / phone") ?></span>
    </div>

    <!-- პაროლი -->
    <div class="txtContainer">
      <label class="txt">
        <?php tr("პაროლი", "password") ?>
        <input id="txtPassword" name="password" type="password" required />
      </label>
      <span id="lblErrorPassword">*<?php tr("პაროლი არასოწრია", "wrong password") ?></span>
    </div>

    <!-- შესვლა -->
    <button id="submit"><?php tr("შესვლა", "login") ?></button>
    <p>
      <?php tr("", "Dont have an account?") ?> <a href="../signup/"><?php tr("რეგისტრაცია", "sign up") ?></a>
    </p>

  </form>

  <script>
    const ui = {
      btnSubmit: document.querySelector("#btnSubmit"),
      txtEmailPhone: document.querySelector("#txtEmailPhone"),
      txtPassword: document.querySelector("#txtPassword"),
      form: document.querySelector("form"),
      lblErrorEmail: document.querySelector("#lblErrorEmail"),
      lblErrorPassword: document.querySelector("#lblErrorPassword"),
    }

    ui.form.addEventListener("submit", e => {
      e.preventDefault()

      ui.lblErrorEmail.style.display = "none"
      ui.lblErrorPassword.style.display = "none"

      JN.post("login", {
          email_phone: ui.txtEmailPhone.value,
          password: ui.txtPassword.value
        },
        res => {

          // სახელი არასწ2ა
          if (res.status === -1) {
            ui.lblErrorEmail.style.display = "block"
          }

          // პაროლი არასწ2ა
          else if (res.status === -2) {
            ui.lblErrorPassword.style.display = "block"
          }

          // წარმატებით შევიდა
          else {
            window.location.reload()
          }
        },
        error => {
          alert(error)
        }
      )
    })
  </script>

  <?php include("../../partials/footer.php"); ?>

</body>

</html>