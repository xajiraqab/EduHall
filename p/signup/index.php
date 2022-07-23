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
  <title>Sign up</title>
  <meta name="description" content="Sign up" />
  <meta property="og:title" content="Edu Hall - Login" />
  <meta property="og:description" content="Edu Hall is one of Georgia’s leading centers for cultural relations and educational opportunities." />
  <meta property="og:url" content="https://www.eduhall.ge/" />
  <meta property="og:site_name" content="one of Georgia’s leading centers for cultural relations and educational opportunities." />
  <meta property="og:image" content="/images/eduhall.png" />
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="signup.css">
  <link rel="icon" type="image/x-icon" href="../../images/favicon.png">
</head>

<body>
  <?php require_once("../../translate.php") ?>
  <?php include("../../partials/header.php") ?>
  <form class="grid">

    <h2><?php tr("რეგისტრაცია", "sign up") ?></h2>

    <!-- ფოსტა -->
    <div class="txtContainer">
      <label class="txt">
        <?php tr("ფოსტა", "email") ?>
        <input id="txtEmail" name="email" type="email" autofocus required />
      </label>
      <span id="lblErrorEmail">*<?php tr("ფოსტა უკვე გამოყენებულია", "email already exists") ?></span>
    </div>

    <!-- ტელეფონი -->
    <div class="txtContainer">
      <label class="txt">
        <?php tr("ტელეფონი", "phone") ?>
        <input id="txtPhone" name="phone" type="text" required />
      </label>
      <span id="lblErrorPhone">*<?php tr("ტელეფონი უკვე გამოყენებულია", "phone already exists") ?></span>
    </div>

    <!-- პაროლი -->
    <label class="txt">
      <?php tr("პაროლი", "password") ?>
      <input id="txtPassword" name="password" type="password" required />
    </label>

    <!-- შესვლა -->
    <button><?php tr("რეგისტრაცია", "sign up") ?></button>
    <p>
      <?php tr("უკვე ხართ რეგისტრირებული?", "Already have an account?") ?> <a href="../login/"><?php tr("შესვლა", "log in") ?></a>
    </p>

    <script>
      const ui = {
        btnSubmit: document.querySelector("#btnSubmit"),
        txtEmail: document.querySelector("#txtEmail"),
        txtPhone: document.querySelector("#txtPhone"),
        txtPassword: document.querySelector("#txtPassword"),
        form: document.querySelector("form"),
        lblErrorEmail: document.querySelector("#lblErrorEmail"),
        lblErrorPhone: document.querySelector("#lblErrorPhone"),
      }

      ui.form.addEventListener("submit", e => {
        e.preventDefault()

        ui.lblErrorEmail.style.display = "none"
        ui.lblErrorPhone.style.display = "none"

        JN.post("signup", {
            email: ui.txtEmail.value,
            phone: ui.txtPhone.value,
            password: ui.txtPassword.value
          },
          res => {

            if (res.status === -1) {
              ui.lblErrorEmail.style.display = "block"
            } else if (res.status === -2) {
              ui.lblErrorPhone.style.display = "block"
            } else {
              JN.post("login", {
                email_phone: ui.txtEmail.value,
                password: ui.txtPassword.value
              }, () => window.location.reload(), error => alert(error))
            }
          },
          error => alert(error)
        )
      })
    </script>
  </form>

  <?php include("../../partials/footer.php"); ?>

</body>

</html>