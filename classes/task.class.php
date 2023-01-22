<?php
require_once('database.class.php');
require_once('UUID.class.php');
class task {

    // create a task
    public static function get_repeating_values() {
        $values = database::connect()->query("SELECT * FROM repeating_values")->fetch_all();
        return $values;
    }
    // Create a task.
    public static function create_task($post, $user_id) {
        $title = mysqli_real_escape_string(database::connect(), $post['title']);
        $desc = mysqli_real_escape_string(database::connect(), $post['description']);
        $start = mysqli_real_escape_string(database::connect(), $post['startTime']);
        $end = mysqli_real_escape_string(database::connect(), $post['endTime']);
        if (!empty($post['rOptions'])) {
            $repeat = mysqli_real_escape_string(database::connect(), $post['rOptions']);
        } else {
            $repeat = 'null';
        }
        $uuid = UUID::generateUUID();

        $insert = database::connect()->query("INSERT INTO tasks (`ID`, `uuid`, `user_id`, `title`, `description`, `start_time`, `end_time`, `repeat_id`, `notification_delay`) 
        VALUES (NULL, '$uuid', '$user_id', '$title', '$desc', '$start', '$end', '$repeat', '0')");
        return $insert;
    }
    // Update a specific task based on uuid.
    public static function update_task($post, $task_id) {
        $title = mysqli_real_escape_string(database::connect(), $post['title']);
        $desc = mysqli_real_escape_string(database::connect(), $post['description']);
        $start = mysqli_real_escape_string(database::connect(), $post['startTime']);
        $end = mysqli_real_escape_string(database::connect(), $post['endTime']);

        $update = database::connect()->query("UPDATE tasks SET title = '$title', description = '$desc', start_time = '$start', end_time = '$end' WHERE uuid = '".$task_id."'");
        return $update;
    }
    // Get every task that can be done (open tasks)
    public static function get_open_tasks($user_id) {
        // $values = ->query("SELECT * FROM repeating_values")->fetch_all();
        $date_now = date('y-m-d h:i:s');
        $tasks = database::connect()->query("SELECT * FROM tasks WHERE user_id = '$user_id' AND start_time < '$date_now' AND end_time > '$date_now' ORDER BY end_time");
        return $tasks;
    }
    // Get every task that can not yet be done (open tasks)
    public static function get_upcoming_tasks($user_id) {
        $date_now = date('y-m-d h:i:s');
        $tasks = database::connect()->query("SELECT * FROM tasks WHERE user_id = '$user_id' AND start_time > '$date_now' ORDER BY start_time");
        return $tasks;
    }
    // Get every task that can no longer be done (expired tasks)
    public static function get_expired_tasks($user_id) {
        $date_now = date('y-m-d h:i:s');
        $tasks = database::connect()->query("SELECT * FROM tasks WHERE user_id = '$user_id' AND end_time < '$date_now' ORDER BY end_time");
        return $tasks;
    }
    // Create return button.
    public static function return_button($where) {
        return '<div class="backButton"> <a href="./'.$where.'"><i class="fas fa-arrow-left"></i></a> </div>';
    }
    // Get a tasks name by its id, to display ect
    public static function get_task_title($task_id) {
        $name = database::connect()->query("SELECT title FROM tasks WHERE uuid = '$task_id'")->fetch_all();
        return $name[0][0];
    }
    // check if a specific tasks exists
    public static function check_task($task_id) {
        $check = database::connect()->query("SELECT * FROM tasks WHERE uuid = '$task_id'");
        return $check;
    }
    // Getting a specific task based on id.
    public static function get_specific_task($task_id) {
        $task = database::connect()->query("SELECT * FROM tasks WHERE uuid = '$task_id'")->fetch_assoc();
        return $task;
    }
    // Validate time
    function validateDate($date) {
        $format = 'Y-m-d';
        $dateform = DateTime::createFromFormat($format, $date);
        return $dateform && $dateform->format($format) === $date;
    }
}
?>