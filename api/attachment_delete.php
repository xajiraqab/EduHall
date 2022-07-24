<?php
require_once("../session.php");
_adminsOnly();

$data = json_decode(file_get_contents('php://input'));

// ფაილის წაშლა
if (file_exists("../data/" . $data->url))
  unlink("../data/" . $data->url);

// ბაზაში წაშლა
$conn = DB::getConnection();

$query = $conn->prepare("DELETE FROM attachments WHERE id = :id");
$query->bindParam(":id", $data->id);
$query->execute();

echo json_encode(['success' => true]);
