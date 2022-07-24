<?php
require_once("../session.php");
_adminsOnly();

include('phpqrcode/qrlib.php');

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
$listFiles = [];
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

  
  // QR-ის გენერაცია
  QRcode::png("https://www.eduhall.ge/activate/?c=" . $code, $code . ".png", QR_ECLEVEL_L, 5);


  $res .= $code . "\n";
  array_push($listFiles, $code . ".png");
}


// ძველი ზიპის წაშლა
if (file_exists("last_book_codes.zip"))
  unlink("last_book_codes.zip");

// ტექსტ ფაილში კოდების ჩაწერა
file_put_contents("_book_codes.txt", $res);



// ზიპის გაკეთება
$zip = new ZipArchive;
$zip->open("last_book_codes.zip", ZipArchive::CREATE);
$zip->addFile("_book_codes.txt");
foreach ($listFiles as $file) {
  $zip->addFile($file);
}
$zip->close();

// ტექსტ ფაილის წაშლა
if (file_exists("_book_codes.txt"))
  unlink("_book_codes.txt");

// გენერირებული QR ფოტოების წაშლა
foreach ($listFiles as $file) {
  if (file_exists($file))
    unlink($file);
}



$conn->commit();

echo json_encode(["success" => true]);
