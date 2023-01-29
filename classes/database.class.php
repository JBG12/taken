<?php

class database {
    public static $host = "localhost";
    public static $user = "root";
    public static $pass = "";
    public static $db = "to_do";

    public static function connect()
    {
        $db = mysqli_connect(self::$host, self::$user, self::$pass, self::$db);
        return $db;
    }
    // Validate user input
    public static function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
}
?>