<?php
require_once("../session.php");

// ბაზაში შენახვა
$data = json_decode(file_get_contents('php://input'));

$user_id = $_user ? $_user["id"] : -1;

$conn  = DB::getConnection();
$query = $conn->prepare("INSERT INTO messages (user_id, email, message) VALUES (:user_id, :email, :message)");
$query->bindParam(":user_id", $user_id);
$query->bindParam(":email",   $data->email);
$query->bindParam(":message", $data->message);
$query->execute();


echo json_encode(['success' => true]);
