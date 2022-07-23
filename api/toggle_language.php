<?php
require_once("../session.php");

$data = json_decode(file_get_contents('php://input'));
if (!isset($_SESSION["is_georgian"]) || !$_SESSION["is_georgian"])
{
  $_SESSION["is_georgian"] = true;
}
else
{
  $_SESSION["is_georgian"] = false;
}