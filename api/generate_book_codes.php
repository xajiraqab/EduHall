<?php
require_once("../session.php");
_adminsOnly();

$data = json_decode(file_get_contents('php://input'));

function random_strings($length_of_string)
{
  // random_bytes returns number of bytes
  // bin2hex converts them into hexadecimal format
  return substr(
    bin2hex(random_bytes($length_of_string)),
    0,
    $length_of_string
  );
}

$conn  = DB::getConnection();
$query = $conn->prepare("INSERT INTO book_codes (book_id, code) VALUES (:book_id, :code)");

$book_id = $data->book_id;
$query->bindValue(":book_id", $book_id);

$count = $data->count;


$list = [];
$res = "";

foreach (Db::select("select code, book_id from book_codes") as $dbCode)
  $list[$dbCode["code"]] = true;

$conn->beginTransaction();
$i = 0;
while ($i < $count) {
  $code = random_strings(8);

  // თუ უკვე არსებობს კოდი, გამოტოვება
  if (isset($list[$code])) {
    continue;
  }
  $i++;
  $list[$code] = true;


  // ბაზაში ჩამატება
  $query->bindValue(":code", $code);

  try {
    $query->execute();
  } catch (Exception $e) {
    $conn->rollBack();
    echo $e->getMessage();
  }

  $res .= $code . "\n";
}
$conn->commit();

echo json_encode(["generatedData" => $res]);
