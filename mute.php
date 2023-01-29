<?php
include('header.php');
include("classes/task.class.php");

if (isset($_SESSION['active'])) {

    $task_id = $_GET['id'];
    $task_exists = task::check_task($task_id);
    // check if task exists
    if (mysqli_num_rows($task_exists) > 0) {
        task::mute_task($task_id);
        header('Location:index?mute=true');
    } else {
        header('Location:index');
    }
} else {
    header('location: login');
}

?>