<?php
require_once("../session.php");

_usersOnly();

$data = json_decode(file_get_contents('php://input'));

// ძველი პაროლის შემოწმება
if (md5($_user["id"] . $data->oldPassword) !== $_user["password"])
  die(json_encode(["status" => -1]));


// ახალი პაროლის ჩაწერა
$newPassword = md5($_user["id"] . $data->newPassword);

$conn = DB::getConnection();
$query = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
$query->bindParam(":id", $_user["id"]);
$query->bindParam(":password", $newPassword);
$query->execute();

echo json_encode(['success' => true]);
