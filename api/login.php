<?php
require_once("../session.php");


$data = json_decode(file_get_contents('php://input'));

//შემოწმება არსებობს თუარა ასეთი username
$user = DB::select_single("SELECT * FROM users WHERE email='$data->email_phone' OR phone='$data->email_phone'");
if (!isset($user)) {
  die(json_encode(["status" => -1]));
}

//შემოწმება პაროლი არის თუარა სწ2
$submitedPassword = md5($user["id"] . $data->password);
if ($submitedPassword !== $user["password"]) {
  die(json_encode(["status" => -2]));
}

$_SESSION["user"] = $user;

echo (json_encode(["success" => true]));
