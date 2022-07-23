<?php
require_once("../session.php");
_adminsOnly();

$data = json_decode($_POST["book"]);
$hasImageChanged = isset($_FILES['image']);
if ($hasImageChanged) {

  // ძველი სურათის წაშლა
  unlink("../images/" . $data->image);

  // სურათის შენახვა
  $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
  $new_name = uniqid() . "." . $extension;

  move_uploaded_file($_FILES["image"]["tmp_name"], "../images/" . $new_name);
}

// ბაზაში შენახვა
$conn = DB::getConnection();
$sql = "UPDATE books SET title = :title, title_geo = :title_geo, authors = :authors, authors_geo = :authors_geo, year = :year, description = :description, description_geo = :description_geo";
if ($hasImageChanged)
  $sql .= ", image = :image";
$query = $conn->prepare($sql . " WHERE id = :id");
$query->bindParam(":id", $data->id);
$query->bindParam(":title", $data->title);
$query->bindParam(":title_geo", $data->title_geo);
$query->bindParam(":authors", $data->authors);
$query->bindParam(":authors_geo", $data->authors_geo);
$query->bindParam(":year", $data->year);
$query->bindParam(":description", $data->description);
$query->bindParam(":description_geo", $data->description_geo);
if ($hasImageChanged)
  $query->bindParam(":image", $new_name);
$query->execute();


echo json_encode(['success' => true]);
