<?php
require_once("../session.php");
_adminsOnly();

$data = json_decode(file_get_contents('php://input'));

// ბაზაში შენახვა
$conn = DB::getConnection();
$query = $conn->prepare("UPDATE attachments SET name = :name, name_geo = :name_geo WHERE id = :id");
$query->bindParam(":id", $data->id);
$query->bindParam(":name", $data->name);
$query->bindParam(":name_geo", $data->name_geo);
$query->execute();

echo json_encode(['success' => true]);
