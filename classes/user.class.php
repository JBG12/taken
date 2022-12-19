<?php
include ("database.class.php");

class user {
    public static $host = "localhost";
    public static $user = "root";
    public static $pass = "";
    public static $db = "to_do";

    public static function connect() {
        $db = mysqli_connect(self::$host, self::$user, self::$pass, self::$db);
        return $db;
    }
    // Hash password function
    public static function hashPassword($password) {
        $password_enq = password_hash($password, PASSWORD_DEFAULT);
        return $password_enq;
    }
    // Check if account exists by the account id
    public static function ExistsAccountByUUID($id) {
        $user = self::connect()->query("SELECT * FROM users WHERE uuid = '".$id."'")->fetch_assoc();
        return $user;
    }
    // Get users password by its UUID
    public static function getPasswordByUUID($user_uuid) {
        $password = self::connect()->query("SELECT * FROM users WHERE uuid = '".$user_uuid."'")->fetch_assoc();
        return $password['password'];
    }
    // Update the password of a user
    public static function updateUserPassword($user_uuid, $hashed_password) {
        $password = self::connect()->query("UPDATE users SET password = '$hashed_password' WHERE uuid = '".$user_uuid."'");
        return $password;
    }

}

?>