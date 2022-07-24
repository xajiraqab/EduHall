<?php
require_once("../session.php");
_adminsOnly();

$data = json_decode($_GET["data"]);

$size = $data->count;
$listFilenames = [];
for ($i = 0; $i < $size; $i++) {
  array_push($listFilenames, uniqid());
}

echo json_encode($listFilenames);
