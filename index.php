<?php
require_once("session.php");
echo $_SESSION["name"];
$_SESSION["allow_download"] = true;

echo json_encode($_GET["is_allowed"]);
?>

<?php header("Location: p/books");