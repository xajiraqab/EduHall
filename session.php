<?php
require_once("translate.php");
require_once("db/DB.php");

session_start();

// მომხმარებლის განახლება თუ შესულია
$_user = $_SESSION["user"] ?? null;
$_isGeorgian = isset($_SESSION["is_georgian"]) && $_SESSION["is_georgian"];

if ($_user) {
  $_user = DB::select_single("SELECT * FROM users WHERE id=" . $_user['id']);

  // თუ ასეთი მომხმარებელი არ მოიძებნა (მერე წაიშალა ანრამე როგორღაც ბაზიდან) ლოგაუთი
  if (!isset($_user)) {
    session_destroy();
  } else {
    $_SESSION["user"] = $_user;
  }
}

function _isAdmin()
{
  global $_user;
  return $_user && $_user["is_admin"];
}


function _usersOnly()
{
  global $_user;
  if (!$_user) {
    header("Location: ../signup");
    die();
  }
}



function _adminsOnly()
{
  global $_user;
  if (!$_user || !$_user["is_admin"]) {
    // header("Location: ../login");
    die("admins only");
  }
}
