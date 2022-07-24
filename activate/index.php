<?php
require_once("../session.php");

$_SESSION["book_code_to_activate"] = $_GET["c"];

header("Location: ../p/profile");