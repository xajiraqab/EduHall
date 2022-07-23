<?php
require_once("../session.php");
_adminsOnly();

$data = json_decode(file_get_contents('php://input'));

// სურათის წაშლა
if (file_exists("../images/" . $data->image))
  unlink("../images/" . $data->image);

// მიმაგრებული ფაილების წაშლა
$listAttachments = Db::getListAttachments($data->id);
foreach ($listAttachments as $attachment) {
  if (file_exists("../data/" . $attachment["url"]))
    unlink("../data/" . $attachment["url"]);
}

// ბაზიდან მიმაგრებული ფაილების წაშლა
$conn = DB::getConnection();
$conn->beginTransaction();
$query = $conn->prepare("DELETE FROM book_codes WHERE book_id = :id");
$query->bindParam(":id", $data->id);
$query->execute();

// ბაზიდან წიგნის კოდების წაშლა
$query = $conn->prepare("DELETE FROM attachments WHERE book_id = :id");
$query->bindParam(":id", $data->id);
$query->execute();

// ბაზაში წაშლა წიგნის
$query = $conn->prepare("DELETE FROM books WHERE id = :id");
$query->bindParam(":id", $data->id);
$query->execute();

$conn->commit();

echo json_encode(['success' => true]);
