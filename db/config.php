<?php

class Config
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
}
