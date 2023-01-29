<?php
require_once('database.class.php');

class user {
    // Hash password function
    public static function hashPassword($password) {
        $password_enq = password_hash($password, PASSWORD_DEFAULT);
        return $password_enq;
    }
    // Check if account exists by the account id
    public static function ExistsAccountByUUID($id) {
        $user = database::connect()->query("SELECT * FROM users WHERE uuid = '".$id."'")->fetch_assoc();
        return $user;
    }
    // Get users password by its UUID
    public static function getPasswordByUUID($user_uuid) {
        $password = database::connect()->query("SELECT * FROM users WHERE uuid = '".$user_uuid."'")->fetch_assoc();
        return $password['password'];
    }
    // Get users password by its UUID
    public static function get_email_by_id($id) {
        $password = database::connect()->query("SELECT * FROM users WHERE id = '".$id."'")->fetch_assoc();
        return $password['email'];
    }
    // Update the password of a user
    public static function updateUserPassword($user_uuid, $hashed_password) {
        $password = database::connect()->query("UPDATE users SET password = '$hashed_password' WHERE uuid = '".$user_uuid."'");
        return $password;
    }
    // Create (insert) user
    public static function create_user($post) {
        $uuid = UUID::generateUUID();
        $email = $post['email'];
        $password = $post['password'];
        // hash (encrypt) password in database for security
        $password_enq = password_hash($password, PASSWORD_DEFAULT);

        $user = database::connect()->query("INSERT INTO users (ID, uuid, email, password, type_id) VALUES ('', '$uuid', '$email', '$password_enq', '3')");
        return $user;
    }
    // Check if user is premium
    public static function user_premium($user_id) {
        $user = database::connect()->query("SELECT type_id FROM users WHERE id = '".$user_id."'")->fetch_all();
        $user_def = $user[0][0];
        $type = database::connect()->query("SELECT name FROM types WHERE ID = '".$user_def."'")->fetch_all();

        return $type[0][0];
    }
    // check user role
    public static function user_role($user_id) {
        $user = database::connect()->query("SELECT admin FROM users WHERE id = '".$user_id."'")->fetch_all();
        $user_def = $user[0][0];

        return $user_def;
    }
    
    // check tasks from user
    public static function user_tasks($user_id) {
        $tasks = database::connect()->query("SELECT * FROM tasks WHERE user_id = '$user_id'");
        return mysqli_num_rows($tasks);
    }
    // Get all users
    public static function get_users() {
        $users = database::connect()->query("SELECT * FROM users");
        return $users;
    }
    // Get every user account type (free, premium ect)
    public static function get_types() {
        $types = database::connect()->query("SELECT * FROM types")->fetch_all();
        return $types;
    }
    // Update user, change type.
    public static function update_user($post) {
        $type = $post['rOptions'];
        $id = $post['id'];

        $update = database::connect()->query("UPDATE users SET type_id = '$type' WHERE ID = '".$id."'");
        return $update;
    }
    // Check if user is using a already used e-mailaddress
    public static function check_email($email) {
        $email = database::connect()->query("SELECT * FROM users WHERE email = '".$email."'")->fetch_all();
        return $email;
    }
    public static function update_role($post) {
        $type = $post['uOptions'];
        $id = $post['idOne'];

        $update = database::connect()->query("UPDATE users SET admin = '$type' WHERE ID = '".$id."'");
        return $update;
    }
    // Get tarief
    public static function get_tarief() {
        $tarief = database::connect()->query("SELECT tarief FROM tarief")->fetch_all();
        return $tarief[0][0];
    }
    // Update tarief
    public static function update_tarief($tarief) {
        $tarief = database::connect()->query("UPDATE tarief SET tarief = '$tarief' WHERE id = '1'");
        return $tarief;
    }
    // Delete a user
    public static function delete_user($post) {
        $id = $post['deleteUser'];
        $del = database::connect()->query("DELETE FROM users WHERE ID = '$id'");
        return $del;
    }

}
?>