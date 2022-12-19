<?php

class database {
    public static $host = "localhost";
    public static $user = "root";
    public static $pass = "";
    public static $db = "to_do";
    // $dbRR = mysqli_connect('localhost', 'deb85590_p92k3t1', 'hB8j1RET', 'deb85590_p92k3t1');
    public static function connect()
    {
        $db = mysqli_connect(self::$host, self::$user, self::$pass, self::$db);
        return $db;
    }
}
?>