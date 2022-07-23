<?php
require_once("../session.php");
_adminsOnly();

if (!isset($_FILES['image']))
  die(json_encode(["error_text" => "image not selected"]));


// სურათის შენახვა
$extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
$new_name = uniqid() . "." . $extension;

move_uploaded_file($_FILES["image"]["tmp_name"], "../images/" . $new_name);


// ბაზაში შენახვა
$data = json_decode($_POST["book"]);

$conn  = DB::getConnection();
$query = $conn->prepare("INSERT INTO books (user_id, title, title_geo, authors, authors_geo, year, description, description_geo, image) VALUES (:user_id, :title, :title_geo, :authors, :authors_geo, :year, :description, :description_geo, :image)");
$query->bindParam(":user_id", $_user["id"]);
$query->bindParam(":title", $data->title);
$query->bindParam(":title_geo", $data->title_geo);
$query->bindParam(":authors", $data->authors);
$query->bindParam(":authors_geo", $data->authors_geo);
$query->bindParam(":year", $data->year);
$query->bindParam(":description", $data->description);
$query->bindParam(":description_geo", $data->description_geo);
$query->bindParam(":image", $new_name);
$query->execute();


echo json_encode(['id' => $conn->lastInsertId()]);
