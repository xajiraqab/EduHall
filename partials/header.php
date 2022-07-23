<?php require_once("../../session.php") ?>
<?php require_once("../../translate.php") ?>
<nav>
  <style>nav{display:flex;width:1000px;max-width:100%;margin:auto;border-radius:0 0 16px 16px;padding:0 2em;gap:3em;background:#fff;box-shadow:2px 4px 16px rgba(0,0,0,.1);font-size:1rem}nav button{margin:0;border:none;background:0 0;height:auto}nav button:hover{filter:none}nav a,nav button{cursor:pointer;text-transform:uppercase;text-decoration:none;display:flex;align-items:center;gap:10px;font-weight:700;padding:.7em 0;transition:color 250ms}nav a:first-of-type{font-size:1.5rem}nav a:hover,nav a:hover svg{color:var(--clr_primary)}img{border-radius:0}#btnLanguage{margin-left:auto}@media only screen and (max-width:600px){html{font-size:12px}nav{padding:10px;gap:1.5em;border-radius:0}#logo{width:39px}#a_books,#logo_text{display:none}#a_contact{margin-left:auto}#btnLanguage{margin-left:0}nav a,nav button{padding:0}}</style>

  <a href="/eduhall.git/">
    <img id="logo" width="68px" src="../../images/favicon.png" alt="logo" style="padding-bottom: 5px;" />
    <span id="logo_text" style="font-weight: normal"><span style="font-weight: bolder; color: #297B99">edu</span> hall</span>
  </a>

  <a id="a_books" href="/eduhall.git/p/books">
    <?php tr("წიგნები", "books") ?>
  </a>

  <a id="a_contact" href="/eduhall.git/p/contact">
    <?php tr("კონტაქტი", "contact") ?>
  </a>

  <button id="btnLanguage">
    <img id="imgLanguage" src="<?php echo isset($_SESSION["is_georgian"]) && $_SESSION["is_georgian"] ? "../../images/united-kingdom.png" : "../../images/georgia.png" ?>" width="24" alt="language" />
  </button>

  <a href="/eduhall.git/p/profile">
    <svg title="user" xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 0 24 24" width="32px" fill="currentColor">
      <path d="M0 0h24v24H0z" fill="none" />
      <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
    </svg>
  </a>

  <script src="../../js/JN.js"></script>
  <script>
    let isGeorgian = <?php echo isset($_SESSION["is_georgian"]) && $_SESSION["is_georgian"] ? "true" : "false" ?>;
    document.querySelector("#btnLanguage").addEventListener("click", e => JN.post("toggle_language", {}, () => window.location.reload()))
  </script>
</nav>