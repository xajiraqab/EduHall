<?php
require_once("../session.php");
require_once("../db/DB.php");

$data = json_decode(file_get_contents('php://input'));

// შემოწმება არსებობს თუარა ასეთი მომხმარებელი
$user = DB::select_single("SELECT * FROM users WHERE email='$data->email' OR phone='$data->phone'");
if (isset($user)) {
  die(json_encode(["status" => $data->email === $user["email"] ? -1 : -2]));
}

// ბაზაში ჩამატება
$conn = DB::getConnection();
$conn->beginTransaction();

// ფოსტის და ტელეფონის ჩაწერა
$query = $conn->prepare("INSERT INTO users (email, phone, is_active) VALUES (:email, :phone, 1)");
$query->bindParam(":email", $data->email);
$query->bindParam(":phone", $data->phone);
$query->execute();

$id = $conn->lastInsertId();
$password = md5($id.$data->password);

// პაროლის ჩაწერა
$query = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
$query->bindParam(":id", $id);
$query->bindParam(":password", $password);
$query->execute();

$conn->commit();

echo json_encode(['success' => true]);
