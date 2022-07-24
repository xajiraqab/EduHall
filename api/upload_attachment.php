<?php
require_once("../session.php");
_adminsOnly();


$new_name = "../data/" . $_POST["filename"];

if (!file_exists($new_name))
  move_uploaded_file($_FILES["data"]["tmp_name"], "../data/" . $new_name);
else
  file_put_contents("../data/" . $new_name, file_get_contents($_FILES["data"]["tmp_name"]), FILE_APPEND);

echo json_encode(["success" => true]);
