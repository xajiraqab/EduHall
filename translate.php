<?php
require_once("session.php");

function tr($word_geo, $word_eng)
{
  echo isset($_SESSION["is_georgian"]) && $_SESSION["is_georgian"] ? $word_geo : $word_eng;
}
