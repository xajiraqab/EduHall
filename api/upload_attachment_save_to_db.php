<?php
require_once("../session.php");
_adminsOnly();

// ბაზაში შენახვა
$data = json_decode(file_get_contents('php://input'));

$conn  = DB::getConnection();
$query = $conn->prepare("INSERT INTO attachments (user_id, url, format, name, name_geo, book_id) VALUES (:user_id, :url, :format, :name, :name_geo, :book_id)");
$query->bindParam(":user_id", $_user["id"]);
$query->bindParam(":url", $data->url);
$query->bindParam(":format", $data->format);
$query->bindParam(":name", $data->name);
$query->bindParam(":name_geo", $data->name_geo);
$query->bindParam(":book_id", $data->book_id);
$query->execute();

echo json_encode(['id' => $conn->lastInsertId()]);
