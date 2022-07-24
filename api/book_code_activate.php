<?php
require_once("../session.php");

if (!$_user)
  die(json_encode(["error" => $_isGeorgian ? "არაავტორიზირებული მცდელობა :3!" : "unauthorized :3!"]));

$data = json_decode(file_get_contents('php://input'));

$book_code = Db::getBookCode($data->code);
if (!$book_code)
  die(json_encode(["error" => $_isGeorgian ? "კოდი არ მოიძებნა!" : "invalid book code!"]));


if ($book_code["user_id"] !== "-1")
  die(json_encode(["error" => $_isGeorgian ? "კოდი უკვე გამოყენებულია!" : "book code is already activated!"]));


$conn = DB::getConnection();

// ბაზაში მომხმარებელზე მიბმა კოდის
$query = $conn->prepare("UPDATE book_codes SET user_id = :user_id, activate_date = CURRENT_TIMESTAMP, max_date = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 YEAR) WHERE id = :id");
$query->bindParam(":id", $book_code["id"]);
$query->bindParam(":user_id", $_user["id"]);
$query->execute();

echo json_encode(["success" => true, "book_name" => $book_code[$_isGeorgian ? "title_geo" : "title"]]);
