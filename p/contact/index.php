<?php require_once("../../session.php") ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title><?php tr("კონტაქტი", "Contact us") ?></title>
  <meta name="description" content="Contact Page" />
  <meta property="og:title" content="EduHall - Contact us" />
  <meta property="og:description" content="Contact us" />
  <meta property="og:url" content="https://www.eduhall.ge/" />
  <meta property="og:site_name" content="Eduhall" />
  <meta property="og:image" content="/images/eduhall.png" />
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="contact.css">
  <link rel="stylesheet" href="contact.js">
  <link rel="icon" type="image/x-icon" href="../../images/favicon.png">
</head>

<body>
  <?php include("../../partials/header.php"); ?>
  <main id="contact">

    <!-- რუკა -->
    <iframe title="map" style="margin-bottom: 2em" width="100%" height="300px" frameborder="0" allowfullscreen src="//umap.openstreetmap.fr/en/map/untitled-map_790616?scaleControl=false&miniMap=false&scrollWheelZoom=false&zoomControl=true&allowEdit=false&moreControl=true&searchControl=null&tilelayersControl=null&embedControl=null&datalayersControl=true&onLoadPanel=undefined&captionBar=false"></iframe>

    <!-- კონტაქტი -->
    <div class="grid_twocol">

      <form id="form_contact">

        <!-- სათაური -->
        <h2><?php tr("დაგვიკავშირდით", "contact us") ?></h2>

        <!-- ფოსტა -->
        <div class="txtContainer">
          <label class="txt">
            <?php tr("ფოსტა", "email") ?>
            <input id="txtEmail" name="email" type="email" value="<?php echo $_user ? $_user["email"] : "" ?>" required />
          </label>
        </div>

        <!-- შეტყობინება -->
        <div class="txtContainer">
          <label class="txt">
            <?php tr("შეტყობინება", "message") ?>
            <textarea id="txtMessage" required></textarea>
          </label>
        </div>

        <!-- გაგზავნა -->
        <button id="btnSend"><?php tr("გაგზავნა", "send message") ?></button>

        <!-- გაგზავნის შეტყობინება -->
        <div id="lblSuccess">
          <svg xmlns="http://www.w3.org/2000/svg" height="124px" viewBox="0 0 24 24" width="124px" fill="#000000">
            <path d="M0 0h24v24H0V0z" fill="none" />
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z" />
          </svg>
          <span><?php tr("შეტყობინება გაიგზავნა", "message sent successfully") ?></span>
        </div>

      </form>

      <!-- მარჯვენა ინფორმაცია -->
      <div style="padding-top: 5.7em">
        <p><strong><?php tr("მისამართი:", "Address:") ?></strong> <?php tr("ყაზბეგის გამზირი #47, თბილისი", "47 Al. Kazbegi Ave. 0160 Tbilisi, Georgia") ?></p>
        <p><strong><?php tr("ტელეფონი:", "Number:") ?></strong> <a href="tel:+995557271005">+995557271005</a></p>
        <p style="margin-top: 2em;"><a href="https://www.facebook.com/eduhall.ge" target="_blank" class="with_image"> <img width="32px" src="../../images/facebook.png" alt="facebook" /> /eduhall.ge</a></p>
      </div>
    </div>

    <script src="contact.js"></script>

  </main>
  <?php include("../../partials/footer.php"); ?>
</body>

</html>