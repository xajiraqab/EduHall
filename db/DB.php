<?php

class DB
{
    public static function getConnection()
    {
        $servername = "localhost";
        $username   = "root";
        $password   = "";
        $dbname     = "eduhall";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return $conn;
    }

    public static function select($sql)
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $conn = null;
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function select_single($sql)
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $conn = null;
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($res) > 0 ? $res[0] : null;
    }

    public static function getListBooks()
    {
        return DB::select("SELECT * FROM books");
    }

    public static function getBook($id, $translate = true)
    {
        if (!is_numeric($id))
            return null;

        $book = Db::select_single("SELECT * FROM books WHERE id = $id");
        if ($translate && isset($book) && isset($_SESSION["is_georgian"]) && $_SESSION["is_georgian"]) {
            $book["title"] = $book["title_geo"];
            $book["authors"] = $book["authors_geo"];
            $book["description"] = $book["description_geo"];
        }
        return $book;
    }

    public static function getListAttachments($book_id)
    {
        return DB::select("SELECT * FROM attachments WHERE book_id = $book_id ORDER BY format desc");
    }

    public static function getListMyBooks($user_id, $book_id = -1, $attachment_url = "")
    {
        $sql = "SELECT DISTINCT b.* FROM `books` b INNER JOIN book_codes bc ON b.id = bc.book_id ";

        if ($attachment_url !== "")
            $sql .= " inner join attachments a on a.book_id = b.id and a.url = '$attachment_url'";

        $sql .= "WHERE bc.user_id = " . $user_id;

        if ($book_id !== -1)
            $sql .= " AND b.id = $book_id ";

        $sql .= " AND max_date >= CURRENT_TIMESTAMP";

        return Db::select($sql);
    }

    public static function isMyBook($user_id, $book_id)
    {
        return count(Db::getListMyBooks($user_id, $book_id));
    }

    public static function isMyAttachment($user_id, $attachment_url)
    {
        return count(Db::getListMyBooks($user_id, -1, $attachment_url));
    }

    public static function getBookCode($book_code)
    {
        return Db::select_single("SELECT b.title, b.title_geo, bc.* FROM `book_codes` bc INNER JOIN books b ON bc.book_id = b.id AND bc.code = '$book_code'");
    }
}
