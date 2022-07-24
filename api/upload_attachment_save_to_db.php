<?php
require_once("../session.php");
_adminsOnly();

// ბაზაში შენახვა
$data = json_decode(file_get_contents('php://input'));

$conn  = DB::getConnection();
$conn->beginTransaction();
$query = $conn->prepare("INSERT INTO attachments (user_id, url, format, name, name_geo, book_id) VALUES (:user_id, :url, :format, :name, :name_geo, :book_id)");

$user_id = $_user["id"];
foreach ($data->listUploadedFiles as $file) {
  $query->bindParam(":user_id", $user_id);
  $query->bindParam(":url", $file->url);
  $query->bindParam(":format", $file->format);
  $query->bindParam(":name", $file->name);
  $query->bindParam(":name_geo", $file->name_geo);
  $query->bindParam(":book_id", $file->book_id);
  $query->execute();
}

$conn->commit();
echo json_encode(['id' => $conn->lastInsertId()]);
